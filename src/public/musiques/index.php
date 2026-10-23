<?php
require __DIR__ . '/../../utils/autoloader.php';
// Ce que je n’ai pas mis : la pochette. La colonne existe dans la base et getPochette() aussi, mais je ne sais pas encore où seront stockées les images. On pourra l’ajouter quand ce sera décidé avec Amel.
use Musiques\MusiquesManager;

// Création d'une instance de MusiquesManager
$musiquesManager = new MusiquesManager();

// Récupération de toutes les musiques
$musiques = $musiquesManager->getMusiques();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musiques | Nom du site à définir</title>
</head>

<body>
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

    <footer>
        <p>Projet réalisé dans le cadre du cours ProgServ2 à la HEIG-VD.</p>
    </footer>
</body>

</html>
