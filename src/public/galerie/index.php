<?php
require __DIR__ . '/../../utils/autoloader.php';

use Images\ImagesManager;

$title = 'Galerie | Zoomy';
$racine = '../';
$page = 'galerie';

// Par défaut : pas d'erreur et aucune image
$erreur = null;
$images = [];

try {
    // Création d'une instance de ImagesManager (connexion à la base)
    $imagesManager = new ImagesManager();

    // Récupération de toutes les images
    $images = $imagesManager->getImages();
} catch (Exception $e) {
    // Si la base ne répond pas, on garde le message pour l'afficher
    $erreur = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre">Galerie d'images</span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <?php if ($erreur !== null) { ?>
                    <!-- Message si la base de données ne répond pas -->
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } elseif (empty($images)) { ?>
                    <p class="message">Aucune image pour le moment.</p>
                <?php } ?>

                <div class="galerie">
                    <?php foreach ($images as $image) { ?>
                        <figure>
                            <img src="../images/galerie/<?= htmlspecialchars($image->getFichier()) ?>" alt="<?= htmlspecialchars($image->getTitre()) ?>">
                            <figcaption>
                                <span><?= htmlspecialchars($image->getTitre()) ?></span>
                                <span class="chip"><?= htmlspecialchars($image->getCategorie()) ?></span>
                            </figcaption>
                        </figure>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
