<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';

use Quiz\QuizManager;

$title = $textes['resultat_titre'] . ' | Zoomy';
$racine = '../';
$page = 'quiz';

$erreur = null;
$aesthetic = null;
$enregistre = false;

// Id du quiz dans l'URL
$idQuiz = 0;

if (isset($_GET['id'])) {
    $idQuiz = (int) $_GET['id'];
}

// Le quiz est-il bien terminé ? (même quiz dans la session et au moins une réponse)
$quizTermine = isset($_SESSION['quiz_id'])
    && $_SESSION['quiz_id'] === $idQuiz
    && !empty($_SESSION['quiz_choix']);

if ($quizTermine) {
    // 1. On compte les points de chaque aesthetic
    //    Exemple : [1, 3, 3, 4] donne [1 => 1, 3 => 2, 4 => 1]
    $points = [];

    foreach ($_SESSION['quiz_choix'] as $idAesthetic) {
        if (!isset($points[$idAesthetic])) {
            $points[$idAesthetic] = 0;
        }

        $points[$idAesthetic] = $points[$idAesthetic] + 1;
    }

    // 2. On cherche l'aesthetic qui a le plus de points
    $idGagnant = 0;
    $maximum = 0;

    foreach ($points as $idAesthetic => $nombre) {
        if ($nombre > $maximum) {
            $maximum = $nombre;
            $idGagnant = $idAesthetic;
        }
    }

    try {
        $quizManager = new QuizManager();

        // 3. On récupère les informations de l'aesthetic gagnant
        $aesthetic = $quizManager->getAesthetic($idGagnant);

        // 4. Si la personne est connectée, on enregistre le résultat (une seule fois)
        if ($aesthetic !== null && isset($_SESSION['utilisateur_id'])) {
            if (!isset($_SESSION['quiz_enregistre'])) {
                $quizManager->addResultat($_SESSION['utilisateur_id'], $idQuiz, $idGagnant);
                $_SESSION['quiz_enregistre'] = true;
            }

            $enregistre = true;
        }
    } catch (Exception $e) {
        $erreur = $textes['erreur_generale'];
    }
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
                <span class="titre"><?= htmlspecialchars($textes['resultat_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps resultat">
                <?php if ($erreur !== null) { ?>
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } elseif ($aesthetic === null) { ?>
                    <p class="message"><?= htmlspecialchars($textes['resultat_pas_fini']) ?></p>
                <?php } else { ?>
                    <p class="texte-doux"><?= htmlspecialchars($textes['resultat_tu_es']) ?></p>
                    <h1><?= htmlspecialchars($aesthetic->getNom()) ?></h1>

                    <?php if ($aesthetic->getImage() !== null) { ?>
                        <img src="../images/aesthetics/<?= htmlspecialchars($aesthetic->getImage()) ?>" alt="<?= htmlspecialchars($aesthetic->getNom()) ?>" class="resultat-image">
                        <p class="texte-doux petit"><?= htmlspecialchars($textes['resultat_source']) ?></p>
                    <?php } ?>

                    <?php if ($aesthetic->getDescription() !== null) { ?>
                        <p><?= htmlspecialchars($aesthetic->getDescription()) ?></p>
                    <?php } ?>

                    <?php if ($enregistre) { ?>
                        <p class="message succes"><?= htmlspecialchars($textes['resultat_enregistre']) ?></p>
                    <?php } else { ?>
                        <p class="message"><?= htmlspecialchars($textes['resultat_connecte_toi']) ?></p>
                    <?php } ?>
                <?php } ?>

                <p>
                    <a href="jouer.php?id=<?= htmlspecialchars($idQuiz) ?>&recommencer=1" class="gel-btn green"><?= htmlspecialchars($textes['resultat_refaire']) ?></a>
                    <a href="index.php" class="gel-btn ghost"><?= htmlspecialchars($textes['quiz_retour']) ?></a>
                </p>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
