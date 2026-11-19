<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';
require_once __DIR__ . '/../../utils/roles.php';

use Quiz\QuizManager;

$racine = '../';
$page = 'admin';

// Page réservée aux admins
exigerAdmin($racine);

// Si un id est dans l'URL, on modifie ce quiz ; sinon, on en ajoute un nouveau
$idQuiz = 0;

if (isset($_GET['id'])) {
    $idQuiz = (int) $_GET['id'];
}

$modeModification = $idQuiz > 0;

// Valeurs du formulaire (vides par défaut)
$titre = '';
$description = '';
$image = '';

$erreurs = [];
$quizIntrouvable = false;

try {
    $quizManager = new QuizManager();

    // En modification, on préremplit le formulaire avec le quiz existant
    if ($modeModification && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $quiz = $quizManager->getQuizParId($idQuiz);

        if ($quiz === null) {
            $quizIntrouvable = true;
        } else {
            $titre = $quiz->getTitre();
            $description = $quiz->getDescription() ?? '';
            $image = $quiz->getImage() ?? '';
        }
    }

    // Le formulaire a été envoyé
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre']);
        $description = trim($_POST['description']);
        $image = trim($_POST['image']);

        // Un champ facultatif vide est enregistré comme null
        $descriptionAEnregistrer = null;
        $imageAEnregistrer = null;

        if ($description !== '') {
            $descriptionAEnregistrer = $description;
        }

        if ($image !== '') {
            $imageAEnregistrer = $image;
        }

        // Validation côté serveur
        $quizManager->validerQuiz($titre);

        if ($modeModification) {
            $quizManager->updateQuiz($idQuiz, $titre, $descriptionAEnregistrer, $imageAEnregistrer);
            header('Location: quiz.php?succes=modifie');
        } else {
            $quizManager->addQuiz($titre, $descriptionAEnregistrer, $imageAEnregistrer);
            header('Location: quiz.php?succes=ajoute');
        }

        exit();
    }
} catch (Exception $e) {
    // Le message de l'exception est le nom d'une clé de traduction
    $cle = $e->getMessage();

    if (isset($textes[$cle])) {
        $erreurs[] = $textes[$cle];
    } else {
        $erreurs[] = $textes['erreur_generale'];
    }
}

// Titre de la page selon le mode
if ($modeModification) {
    $titrePage = $textes['admin_quiz_modifier_titre'];
} else {
    $titrePage = $textes['admin_quiz_ajouter_titre'];
}

$title = $titrePage . ' | Zoomy';
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($langue) ?>">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre"><?= htmlspecialchars($titrePage) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <p>
                    <a href="quiz.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['admin_retour_liste']) ?></a>
                </p>

                <?php if ($quizIntrouvable) { ?>
                    <p class="message erreur"><?= htmlspecialchars($textes['admin_quiz_introuvable']) ?></p>
                <?php } else { ?>

                    <?php if (!empty($erreurs)) { ?>
                        <div class="message erreur">
                            <ul>
                                <?php foreach ($erreurs as $erreur) { ?>
                                    <li><?= htmlspecialchars($erreur) ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <form action="quiz-formulaire.php<?= $modeModification ? '?id=' . htmlspecialchars($idQuiz) : '' ?>" method="POST" class="formulaire">
                        <label for="titre"><?= htmlspecialchars($textes['champ_titre']) ?></label>
                        <input type="text" id="titre" name="titre" class="aero-input" value="<?= htmlspecialchars($titre) ?>" required minlength="3" maxlength="255">

                        <label for="description"><?= htmlspecialchars($textes['champ_description']) ?></label>
                        <textarea id="description" name="description" class="aero-input" rows="4"><?= htmlspecialchars($description) ?></textarea>

                        <label for="image"><?= htmlspecialchars($textes['champ_image_quiz']) ?></label>
                        <input type="text" id="image" name="image" class="aero-input" value="<?= htmlspecialchars($image) ?>">
                        <p class="texte-doux petit"><?= htmlspecialchars($textes['champ_image_quiz_aide']) ?></p>

                        <div class="quiz-actions">
                            <button type="submit" class="gel-btn green"><?= htmlspecialchars($textes['admin_enregistrer']) ?></button>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
