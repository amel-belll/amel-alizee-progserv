<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';

use Quiz\QuizManager;

$racine = '../';
$page = 'quiz';

$erreur = null;
$quiz = null;
$questions = [];

// Id du quiz dans l'URL, transformé en nombre entier
$idQuiz = 0;

if (isset($_GET['id'])) {
    $idQuiz = (int) $_GET['id'];
}

try {
    $quizManager = new QuizManager();
    $quiz = $quizManager->getQuizParId($idQuiz);

    if ($quiz !== null) {
        $questions = $quizManager->getQuestions($idQuiz);
    }
} catch (Exception $e) {
    $erreur = $textes['erreur_generale'];
}

// Si on commence un nouveau quiz : on remet la progression à zéro dans la session
if (!isset($_SESSION['quiz_id']) || $_SESSION['quiz_id'] !== $idQuiz) {
    $_SESSION['quiz_id'] = $idQuiz;
    $_SESSION['quiz_position'] = 0;
    $_SESSION['quiz_choix'] = [];
}

$position = $_SESSION['quiz_position'];
$nombreQuestions = count($questions);

// Le formulaire a été envoyé : on enregistre la réponse choisie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $quiz !== null && $position < $nombreQuestions) {
    $questionActuelle = $questions[$position];
    $idReponseChoisie = (int) $_POST['reponse'];

    // On vérifie que la réponse fait bien partie de la question actuelle
    foreach ($questionActuelle->getReponses() as $reponse) {
        if ($reponse->getId() === $idReponseChoisie) {
            // On garde l'aesthetic de cette réponse et on passe à la question suivante
            $_SESSION['quiz_choix'][] = $reponse->getIdAesthetic();
            $_SESSION['quiz_position'] = $position + 1;
        }
    }

    // Dernière question répondue : page de résultat, sinon question suivante
    if ($_SESSION['quiz_position'] >= $nombreQuestions) {
        header('Location: resultat.php?id=' . $idQuiz);
    } else {
        header('Location: jouer.php?id=' . $idQuiz);
    }
    exit();
}

// Pourcentage pour la barre de progression
$pourcentage = 0;

if ($nombreQuestions > 0) {
    $pourcentage = round($position / $nombreQuestions * 100);
}

// Titre de l'onglet du navigateur
if ($quiz !== null) {
    $title = $quiz->getTitre() . ' | Zoomy';
} else {
    $title = $textes['quiz_introuvable'] . ' | Zoomy';
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($langue) ?>">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window quiz-jeu">
            <div class="aero-titlebar">
                <span class="titre"><?= $quiz !== null ? htmlspecialchars($quiz->getTitre()) : htmlspecialchars($textes['quiz_introuvable']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <?php if ($erreur !== null) { ?>
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } elseif ($quiz === null || $nombreQuestions === 0) { ?>
                    <p class="message"><?= htmlspecialchars($textes['quiz_inexistant']) ?></p>
                    <a href="index.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['quiz_retour']) ?></a>
                <?php } else { ?>
                    <?php $question = $questions[$position]; ?>

                    <!-- Progression -->
                    <div class="quiz-progression-texte">
                        <span><?= htmlspecialchars($textes['quiz_question']) ?> <?= $position + 1 ?> / <?= $nombreQuestions ?></span>
                        <span><?= $pourcentage ?>%</span>
                    </div>
                    <div class="quiz-barre">
                        <div class="quiz-barre-remplie" style="width: <?= $pourcentage ?>%;"></div>
                    </div>

                    <!-- Question actuelle -->
                    <h2 class="quiz-question"><?= htmlspecialchars($question->getTexte()) ?></h2>

                    <form action="jouer.php?id=<?= htmlspecialchars($quiz->getId()) ?>" method="POST">
                        <div class="quiz-choix">
                            <?php foreach ($question->getReponses() as $reponse) { ?>
                                <label class="choix">
                                    <input type="radio" name="reponse" value="<?= htmlspecialchars($reponse->getId()) ?>" required>
                                    <?= htmlspecialchars($reponse->getTexte()) ?>
                                </label>
                            <?php } ?>
                        </div>

                        <div class="quiz-actions">
                            <button type="submit" class="gel-btn green">
                                <?php if ($position + 1 === $nombreQuestions) { ?>
                                    <?= htmlspecialchars($textes['quiz_voir_resultat']) ?>
                                <?php } else { ?>
                                    <?= htmlspecialchars($textes['quiz_suivant']) ?>
                                <?php } ?>
                            </button>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
