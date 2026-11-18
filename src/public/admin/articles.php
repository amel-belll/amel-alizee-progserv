<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';
require_once __DIR__ . '/../../utils/roles.php';

use Articles\ArticlesManager;

$title = $textes['onglet_admin_articles'];
$racine = '../';
$page = 'admin';

// Page réservée aux admins
exigerAdmin($racine);

$erreur = null;
$articles = [];

try {
    $articlesManager = new ArticlesManager();
    $articles = $articlesManager->getArticles();
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
                <span class="titre"><?= htmlspecialchars($textes['admin_articles_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <p>
                    <a href="article-formulaire.php" class="gel-btn green"><?= htmlspecialchars($textes['admin_ajouter']) ?></a>
                </p>

                <?php if ($erreur !== null) { ?>
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } ?>

                <table class="tableau-admin">
                    <thead>
                        <tr>
                            <th><?= htmlspecialchars($textes['admin_col_titre']) ?></th>
                            <th><?= htmlspecialchars($textes['admin_col_categorie']) ?></th>
                            <th><?= htmlspecialchars($textes['admin_col_date']) ?></th>
                            <th><?= htmlspecialchars($textes['admin_col_actions']) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article) { ?>
                            <tr>
                                <td><?= htmlspecialchars($article->getTitre()) ?></td>
                                <td><span class="chip"><?= htmlspecialchars($article->getCategorie()) ?></span></td>
                                <td><?= htmlspecialchars($article->getDatePublication()->format('d.m.Y')) ?></td>
                                <td>
                                    <a href="article-formulaire.php?id=<?= htmlspecialchars($article->getId()) ?>" class="gel-btn sm ghost"><?= htmlspecialchars($textes['admin_modifier']) ?></a>
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
