<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';
require_once __DIR__ . '/../../utils/roles.php';

use Quiz\QuizManager;

$title = $textes['onglet_mes_resultats'];
$racine = '../';
$page = 'mes-resultats';

// Page réservée aux membres : sinon, redirection vers la connexion
exigerConnexion($racine);

$erreur = null;
$resultats = [];

try {
    $quizManager = new QuizManager();

    // Les résultats de la personne connectée
    $resultats = $quizManager->getResultatsUtilisateur($_SESSION['utilisateur_id']);
} catch (Exception $e) {
    $erreur = $textes['erreur_generale'];
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
                <span class="titre"><?= htmlspecialchars($textes['mes_resultats_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <?php if ($erreur !== null) { ?>
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } elseif (empty($resultats)) { ?>
                    <p class="message"><?= htmlspecialchars($textes['mes_resultats_vide']) ?></p>
                    <a href="../quiz/" class="gel-btn green"><?= htmlspecialchars($textes['mes_resultats_faire']) ?></a>
                <?php } ?>

                <!-- Grille de cartes : un résultat par carte -->
                <div class="grille">
                    <?php foreach ($resultats as $resultat) { ?>
                        <div class="carte">
                            <div class="carte-image">
                                <?php if ($resultat->getImageAesthetic() !== null) { ?>
                                    <img src="../images/aesthetics/<?= htmlspecialchars($resultat->getImageAesthetic()) ?>" alt="<?= htmlspecialchars($resultat->getNomAesthetic()) ?>">
                                <?php } ?>
                            </div>
                            <div class="carte-corps">
                                <div class="carte-infos">
                                    <span class="chip"><?= htmlspecialchars($resultat->getTitreQuiz()) ?></span>
                                    <?= htmlspecialchars($resultat->getDate()->format('d.m.Y')) ?>
                                </div>
                                <h3><?= htmlspecialchars($resultat->getNomAesthetic()) ?></h3>
                                <a href="../quiz/jouer.php?id=<?= htmlspecialchars($resultat->getIdQuiz()) ?>&recommencer=1" class="gel-btn sm ghost"><?= htmlspecialchars($textes['resultat_refaire']) ?></a>
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
