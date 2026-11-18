<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';
require_once __DIR__ . '/../../utils/roles.php';

$title = $textes['onglet_admin'];
$racine = '../';
$page = 'admin';

// Page réservée aux admins
exigerAdmin($racine);
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($langue) ?>">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre"><?= htmlspecialchars($textes['admin_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <p><?= htmlspecialchars($textes['admin_intro']) ?></p>

                <div class="admin-menu">
                    <a href="articles.php" class="gel-btn"><?= htmlspecialchars($textes['admin_lien_articles']) ?></a>
                    <a href="musiques.php" class="gel-btn"><?= htmlspecialchars($textes['admin_lien_musiques']) ?></a>
                    <a href="images.php" class="gel-btn"><?= htmlspecialchars($textes['admin_lien_images']) ?></a>
                    <a href="quiz.php" class="gel-btn"><?= htmlspecialchars($textes['admin_lien_quiz']) ?></a>
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
