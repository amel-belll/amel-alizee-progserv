<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';
require_once __DIR__ . '/../../utils/roles.php';

use Images\ImagesManager;

$title = $textes['onglet_admin_images'];
$racine = '../';
$page = 'admin';

// Page réservée aux admins
exigerAdmin($racine);

$erreur = null;
$succes = null;
$images = [];

// Message après un ajout ou une modification
if (isset($_GET['succes']) && $_GET['succes'] === 'ajoute') {
    $succes = $textes['admin_image_ajoutee'];
}

if (isset($_GET['succes']) && $_GET['succes'] === 'modifie') {
    $succes = $textes['admin_image_modifiee'];
}

try {
    $imagesManager = new ImagesManager();

    // Le formulaire « Supprimer » a été envoyé
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_supprimer'])) {
        $imagesManager->removeImage((int) $_POST['id_supprimer']);
        $succes = $textes['admin_image_supprimee'];
    }

    $images = $imagesManager->getImages();
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
                <span class="titre"><?= htmlspecialchars($textes['admin_images_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <p>
                    <a href="index.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['admin_titre']) ?></a>
                    <a href="image-formulaire.php" class="gel-btn green"><?= htmlspecialchars($textes['admin_ajouter']) ?></a>
                </p>

                <?php if ($erreur !== null) { ?>
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } ?>

                <?php if ($succes !== null) { ?>
                    <p class="message succes"><?= htmlspecialchars($succes) ?></p>
                <?php } ?>

                <table class="tableau-admin">
                    <thead>
                        <tr>
                            <th><?= htmlspecialchars($textes['admin_col_image']) ?></th>
                            <th><?= htmlspecialchars($textes['admin_col_titre']) ?></th>
                            <th><?= htmlspecialchars($textes['admin_col_categorie']) ?></th>
                            <th><?= htmlspecialchars($textes['admin_col_actions']) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($images as $image) { ?>
                            <tr>
                                <td><img src="../images/galerie/<?= htmlspecialchars($image->getFichier()) ?>" alt="" class="miniature-admin"></td>
                                <td><?= htmlspecialchars($image->getTitre()) ?></td>
                                <td><span class="chip"><?= htmlspecialchars($image->getCategorie()) ?></span></td>
                                <td>
                                    <div class="actions-admin">
                                        <a href="image-formulaire.php?id=<?= htmlspecialchars($image->getId()) ?>" class="gel-btn sm ghost"><?= htmlspecialchars($textes['admin_modifier']) ?></a>

                                        <form action="images.php" method="POST" onsubmit="return confirm('<?= htmlspecialchars($textes['admin_confirmer_suppression_image']) ?>');">
                                            <input type="hidden" name="id_supprimer" value="<?= htmlspecialchars($image->getId()) ?>">
                                            <button type="submit" class="gel-btn sm red"><?= htmlspecialchars($textes['admin_supprimer']) ?></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
