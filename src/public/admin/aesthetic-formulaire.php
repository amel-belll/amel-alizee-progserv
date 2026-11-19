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

// Id du quiz (obligatoire) et id de l'aesthetic (seulement pour modifier)
$idQuiz = 0;
$idAesthetic = 0;

if (isset($_GET['quiz'])) {
    $idQuiz = (int) $_GET['quiz'];
}

if (isset($_GET['id'])) {
    $idAesthetic = (int) $_GET['id'];
}

$modeModification = $idAesthetic > 0;

$nom = '';
$description = '';
$image = '';

$erreurs = [];
$introuvable = false;

try {
    $quizManager = new QuizManager();

    // Le quiz doit exister
    if ($quizManager->getQuizParId($idQuiz) === null) {
        $introuvable = true;
    }

    // En modification, on préremplit le formulaire
    if (!$introuvable && $modeModification && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $aesthetic = $quizManager->getAesthetic($idAesthetic);

        if ($aesthetic === null) {
            $introuvable = true;
        } else {
            $nom = $aesthetic->getNom();
            $description = $aesthetic->getDescription() ?? '';
            $image = $aesthetic->getImage() ?? '';
        }
    }

    // Le formulaire a été envoyé
    if (!$introuvable && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom']);
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
        $quizManager->validerAesthetic($nom);

        if ($modeModification) {
            $quizManager->updateAesthetic($idAesthetic, $idQuiz, $nom, $descriptionAEnregistrer, $imageAEnregistrer);
            header('Location: aesthetics.php?quiz=' . $idQuiz . '&succes=modifie');
        } else {
            $quizManager->addAesthetic($idQuiz, $nom, $descriptionAEnregistrer, $imageAEnregistrer);
            header('Location: aesthetics.php?quiz=' . $idQuiz . '&succes=ajoute');
        }

        exit();
    }
} catch (Exception $e) {
    $cle = $e->getMessage();

    if (isset($textes[$cle])) {
        $erreurs[] = $textes[$cle];
    } else {
        $erreurs[] = $textes['erreur_generale'];
    }
}

if ($modeModification) {
    $titrePage = $textes['admin_aesthetic_modifier_titre'];
} else {
    $titrePage = $textes['admin_aesthetic_ajouter_titre'];
}

$title = $titrePage . ' | Zoomy';

// Adresse du formulaire (on garde l'id du quiz, et l'id de l'aesthetic en modification)
$actionFormulaire = 'aesthetic-formulaire.php?quiz=' . $idQuiz;

if ($modeModification) {
    $actionFormulaire = $actionFormulaire . '&id=' . $idAesthetic;
}
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
                    <a href="aesthetics.php?quiz=<?= htmlspecialchars($idQuiz) ?>" class="gel-btn ghost sm"><?= htmlspecialchars($textes['admin_retour_aesthetics']) ?></a>
                </p>

                <?php if ($introuvable) { ?>
                    <p class="message erreur"><?= htmlspecialchars($textes['admin_aesthetic_introuvable']) ?></p>
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

                    <form action="<?= htmlspecialchars($actionFormulaire) ?>" method="POST" class="formulaire">
                        <label for="nom"><?= htmlspecialchars($textes['champ_nom']) ?></label>
                        <input type="text" id="nom" name="nom" class="aero-input" value="<?= htmlspecialchars($nom) ?>" required minlength="2" maxlength="100">

                        <label for="description"><?= htmlspecialchars($textes['champ_description']) ?></label>
                        <textarea id="description" name="description" class="aero-input" rows="4"><?= htmlspecialchars($description) ?></textarea>

                        <label for="image"><?= htmlspecialchars($textes['champ_image_aesthetic']) ?></label>
                        <input type="text" id="image" name="image" class="aero-input" value="<?= htmlspecialchars($image) ?>">
                        <p class="texte-doux petit"><?= htmlspecialchars($textes['champ_image_aesthetic_aide']) ?></p>

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
