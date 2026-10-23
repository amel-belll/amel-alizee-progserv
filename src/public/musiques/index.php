<?php
require __DIR__ . '/../../utils/autoloader.php';

use Musiques\MusiquesManager;

$title = 'Musiques | Zoomy';
$racine = '../';

// Création d'une instance de MusiquesManager
$musiquesManager = new MusiquesManager();

// Récupération de toutes les musiques
$musiques = $musiquesManager->getMusiques();
?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <h1>Musiques du moment</h1>

    <p><a href="../index.php">Accueil</a> > Musiques</p>

    <?php foreach ($musiques as $musique) { ?>
        <article>
            <h2><?= htmlspecialchars($musique->getTitre()) ?></h2>
            <p>
                <?= htmlspecialchars($musique->getArtiste()) ?>
                <?php if ($musique->getGenre() !== null) { ?>
                    · <?= htmlspecialchars($musique->getGenre()) ?>
                <?php } ?>
            </p>
            <iframe
                width="560"
                height="315"
                src="https://www.youtube.com/embed/<?= htmlspecialchars($musique->getLien()) ?>"
                title="<?= htmlspecialchars($musique->getTitre()) ?>"
                allowfullscreen>
            </iframe>
        </article>
    <?php } ?>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
