<?php
require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';

use Quiz\QuizManager;

$title = $textes['onglet_quiz']; // ← modifié
$racine = '../';
$page = 'quiz';

// Par défaut : pas d'erreur et aucun quiz
$erreur = null;
$quizListe = [];

try {
    // Création d'une instance de QuizManager (connexion à la base)
    $quizManager = new QuizManager();

    // Récupération de tous les quiz
    $quizListe = $quizManager->getQuiz();
} catch (Exception $e) {
    // Si la base ne répond pas, on garde le message pour l'afficher
    $erreur = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($langue) ?>"><!-- ← modifié -->

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre"><?= htmlspecialchars($textes['quiz_titre']) ?></span><!-- ← modifié -->
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <?php if ($erreur !== null) { ?>
                    <!-- Message si la base de données ne répond pas -->
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } elseif (empty($quizListe)) { ?>
                    <p class="message"><?= htmlspecialchars($textes['quiz_vide']) ?></p><!-- ← modifié -->
                <?php } ?>

                <!-- Grille de cartes -->
                <div class="grille">
                    <?php foreach ($quizListe as $quiz) { ?>
                        <div class="carte">
                            <div class="carte-image">
                                <?php if ($quiz->getImage() !== null) { ?>
                                    <img src="../images/quiz/<?= htmlspecialchars($quiz->getImage()) ?>" alt="">
                                <?php } ?>
                            </div>
                            <div class="carte-corps">
                                <h3><?= htmlspecialchars($quiz->getTitre()) ?></h3>

                                <?php if ($quiz->getDescription() !== null) { ?>
                                    <p class="texte-doux"><?= htmlspecialchars($quiz->getDescription()) ?></p>
                                <?php } ?>

                                <a href="jouer.php?id=<?= htmlspecialchars($quiz->getId()) ?>" class="gel-btn green"><?= htmlspecialchars($textes['quiz_faire']) ?></a><!-- ← modifié -->
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
