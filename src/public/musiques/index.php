<?php
require __DIR__ . '/../../utils/autoloader.php';

use Musiques\MusiquesManager;

$title = 'Musiques | Zoomy';
$racine = '../';
$page = 'musiques';

// Par défaut : pas d'erreur et aucune musique
$erreur = null;
$musiques = [];

try {
    // Création d'une instance de MusiquesManager (connexion à la base)
    $musiquesManager = new MusiquesManager();

    // Récupération de toutes les musiques
    $musiques = $musiquesManager->getMusiques();
} catch (Exception $e) {
    // Si la base ne répond pas, on garde le message pour l'afficher
    $erreur = $e->getMessage();
}

// Musique affichée dans le lecteur : celle choisie dans l'URL (?id=…), sinon la première
$musiqueChoisie = null;

if (!empty($musiques)) {
    $musiqueChoisie = $musiques[0];
}

if (isset($_GET["id"])) {
    $idChoisi = (int) $_GET["id"];

    foreach ($musiques as $musique) {
        if ($musique->getId() === $idChoisi) {
            $musiqueChoisie = $musique;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main class="musiques">
        <!-- Colonne de gauche : la liste des musiques -->
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre">Musiques du moment</span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="liste-musiques">
                <?php if ($erreur !== null) { ?>
                    <!-- Message si la base de données ne répond pas -->
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } elseif (empty($musiques)) { ?>
                    <p class="message">Aucune musique pour le moment.</p>
                <?php } ?>

                <?php foreach ($musiques as $musique) { ?>
                    <div class="ligne-musique">
                        <?php if ($musique->getPochette() !== null) { ?>
                            <img src="../images/pochettes/<?= htmlspecialchars($musique->getPochette()) ?>" alt="" class="pochette">
                        <?php } else { ?>
                            <div class="pochette"></div>
                        <?php } ?>

                        <div class="infos">
                            <p><b><?= htmlspecialchars($musique->getTitre()) ?></b></p>
                            <p class="texte-doux"><?= htmlspecialchars($musique->getArtiste()) ?></p>
                        </div>

                        <?php if ($musique->getGenre() !== null) { ?>
                            <span class="chip"><?= htmlspecialchars($musique->getGenre()) ?></span>
                        <?php } ?>

                        <a href="index.php?id=<?= htmlspecialchars($musique->getId()) ?>" class="gel-btn sm">▶ Écouter</a>
                    </div>
                <?php } ?>
            </div>
        </section>

        <!-- Colonne de droite : le lecteur YouTube -->
        <?php if ($musiqueChoisie !== null) { ?>
            <section class="aero-window">
                <div class="aero-titlebar">
                    <span class="titre">YouTube</span>
                    <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
                </div>

                <div class="fenetre-corps">
                    <iframe
                        class="lecteur"
                        src="https://www.youtube.com/embed/<?= htmlspecialchars($musiqueChoisie->getLien()) ?>"
                        title="<?= htmlspecialchars($musiqueChoisie->getTitre()) ?>"
                        allowfullscreen>
                    </iframe>
                    <p><b><?= htmlspecialchars($musiqueChoisie->getTitre()) ?></b></p>
                    <p class="texte-doux"><?= htmlspecialchars($musiqueChoisie->getArtiste()) ?></p>
                </div>
            </section>
        <?php } ?>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
