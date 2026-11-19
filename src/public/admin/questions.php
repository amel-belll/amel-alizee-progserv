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

if (isset($_GET['quiz'])) {
    $idQuiz = (int) $_GET['quiz'];
}

$erreur = null;
$succes = null;
$quiz = null;
$questions = [];

// Messages après un ajout ou une modification
if (isset($_GET['succes']) && $_GET['succes'] === 'ajoute') {
    $succes = $textes['admin_question_ajoutee'];
}

if (isset($_GET['succes']) && $_GET['succes'] === 'modifie') {
    $succes = $textes['admin_question_modifiee'];
}

try {
    $quizManager = new QuizManager();
    $quiz = $quizManager->getQuizParId($idQuiz);

    if ($quiz !== null) {
        // Le formulaire « Supprimer » a été envoyé
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_supprimer'])) {
            $quizManager->removeQuestion((int) $_POST['id_supprimer'], $idQuiz);
            $succes = $textes['admin_question_supprimee'];
        }

        // getQuestions existe déjà (on l'utilise aussi pour passer le quiz)
        $questions = $quizManager->getQuestions($idQuiz);
    }
} catch (Exception $e) {
    $erreur = $textes['erreur_generale'];
}

$title = $textes['admin_questions_titre'] . ' | Zoomy';
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($langue) ?>">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre"><?= htmlspecialchars($textes['admin_questions_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <p>
                    <a href="quiz.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['admin_retour_quiz']) ?></a>
                </p>

                <?php if ($erreur !== null) { ?>
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } elseif ($quiz === null) { ?>
                    <p class="message erreur"><?= htmlspecialchars($textes['admin_quiz_introuvable']) ?></p>
                <?php } else { ?>
                    <h2><?= htmlspecialchars($quiz->getTitre()) ?></h2>

                    <p>
                        <a href="question-formulaire.php?quiz=<?= htmlspecialchars($idQuiz) ?>" class="gel-btn green"><?= htmlspecialchars($textes['admin_ajouter']) ?></a>
                    </p>

                    <?php if ($succes !== null) { ?>
                        <p class="message succes"><?= htmlspecialchars($succes) ?></p>
                    <?php } ?>

                    <table class="tableau-admin">
                        <thead>
                            <tr>
                                <th><?= htmlspecialchars($textes['admin_col_ordre']) ?></th>
                                <th><?= htmlspecialchars($textes['admin_col_question']) ?></th>
                                <th><?= htmlspecialchars($textes['admin_col_reponses']) ?></th>
                                <th><?= htmlspecialchars($textes['admin_col_actions']) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questions as $numero => $question) { ?>
                                <tr>
                                    <td><?= $numero + 1 ?></td>
                                    <td><?= htmlspecialchars($question->getTexte()) ?></td>
                                    <td><?= count($question->getReponses()) ?></td>
                                    <td>
                                        <div class="actions-admin">
                                            <a href="question-formulaire.php?quiz=<?= htmlspecialchars($idQuiz) ?>&id=<?= htmlspecialchars($question->getId()) ?>" class="gel-btn sm ghost"><?= htmlspecialchars($textes['admin_modifier']) ?></a>

                                            <form action="questions.php?quiz=<?= htmlspecialchars($idQuiz) ?>" method="POST" onsubmit="return confirm('<?= htmlspecialchars($textes['admin_confirmer_suppression_question']) ?>');">
                                                <input type="hidden" name="id_supprimer" value="<?= htmlspecialchars($question->getId()) ?>">
                                                <button type="submit" class="gel-btn sm red"><?= htmlspecialchars($textes['admin_supprimer']) ?></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
