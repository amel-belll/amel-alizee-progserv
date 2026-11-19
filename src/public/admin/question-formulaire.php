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

$idQuiz = 0;
$idQuestion = 0;

if (isset($_GET['quiz'])) {
    $idQuiz = (int) $_GET['quiz'];
}

if (isset($_GET['id'])) {
    $idQuestion = (int) $_GET['id'];
}

$modeModification = $idQuestion > 0;

$texte = '';
$reponses = []; // [id de l'aesthetic => texte de la réponse]
$aesthetics = [];

$erreurs = [];
$introuvable = false;

try {
    $quizManager = new QuizManager();

    // Le quiz doit exister
    if ($quizManager->getQuizParId($idQuiz) === null) {
        $introuvable = true;
    } else {
        // Les aesthetics du quiz : une ligne de réponse par aesthetic
        $aesthetics = $quizManager->getAesthetics($idQuiz);
    }

    // En modification, on préremplit le formulaire
    if (!$introuvable && $modeModification && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $question = $quizManager->getQuestion($idQuestion, $idQuiz);

        if ($question === null) {
            $introuvable = true;
        } else {
            $texte = $question->getTexte();

            foreach ($question->getReponses() as $reponse) {
                $reponses[$reponse->getIdAesthetic()] = $reponse->getTexte();
            }
        }
    }

    // Le formulaire a été envoyé
    if (!$introuvable && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $texte = trim($_POST['texte']);
        $reponses = [];

        // On ne garde que les réponses des aesthetics de ce quiz
        foreach ($aesthetics as $aesthetic) {
            $idAesthetic = $aesthetic->getId();

            if (isset($_POST['reponses'][$idAesthetic])) {
                $reponses[$idAesthetic] = $_POST['reponses'][$idAesthetic];
            }
        }

        // Validation côté serveur
        $quizManager->validerQuestion($texte, $reponses);

        if ($modeModification) {
            $quizManager->updateQuestion($idQuestion, $idQuiz, $texte, $reponses);
            header('Location: questions.php?quiz=' . $idQuiz . '&succes=modifie');
        } else {
            $quizManager->addQuestion($idQuiz, $texte, $reponses);
            header('Location: questions.php?quiz=' . $idQuiz . '&succes=ajoute');
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
    $titrePage = $textes['admin_question_modifier_titre'];
} else {
    $titrePage = $textes['admin_question_ajouter_titre'];
}

$title = $titrePage . ' | Zoomy';

$actionFormulaire = 'question-formulaire.php?quiz=' . $idQuiz;

if ($modeModification) {
    $actionFormulaire = $actionFormulaire . '&id=' . $idQuestion;
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
                    <a href="questions.php?quiz=<?= htmlspecialchars($idQuiz) ?>" class="gel-btn ghost sm"><?= htmlspecialchars($textes['admin_retour_questions']) ?></a>
                </p>

                <?php if ($introuvable) { ?>
                    <p class="message erreur"><?= htmlspecialchars($textes['admin_question_introuvable']) ?></p>
                <?php } elseif (empty($aesthetics)) { ?>
                    <p class="message"><?= htmlspecialchars($textes['admin_aucun_aesthetic']) ?></p>
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
                        <label for="texte"><?= htmlspecialchars($textes['champ_question']) ?></label>
                        <input type="text" id="texte" name="texte" class="aero-input" value="<?= htmlspecialchars($texte) ?>" required minlength="3" maxlength="255">

                        <p class="texte-doux petit"><?= htmlspecialchars($textes['admin_reponses_aide']) ?></p>

                        <!-- Une réponse par aesthetic du quiz -->
                        <?php foreach ($aesthetics as $aesthetic) { ?>
                            <label for="reponse-<?= htmlspecialchars($aesthetic->getId()) ?>">
                                <?= htmlspecialchars($textes['admin_reponse_pour']) ?> <span class="chip"><?= htmlspecialchars($aesthetic->getNom()) ?></span>
                            </label>
                            <input
                                type="text"
                                id="reponse-<?= htmlspecialchars($aesthetic->getId()) ?>"
                                name="reponses[<?= htmlspecialchars($aesthetic->getId()) ?>]"
                                class="aero-input"
                                maxlength="255"
                                value="<?= htmlspecialchars($reponses[$aesthetic->getId()] ?? '') ?>">
                        <?php } ?>

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
