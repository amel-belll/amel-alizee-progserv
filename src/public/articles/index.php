<?php
require __DIR__ . '/../../utils/autoloader.php';

use Articles\ArticlesManager;

$title = 'Articles | Zoomy';
$racine = '../';

// Création d'une instance de ArticlesManager
$articlesManager = new ArticlesManager();

// Récupération de tous les articles
$articles = $articlesManager->getArticles();
?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <h1>Articles</h1>

    <p><a href="../index.php">Accueil</a> > Articles</p>

    <?php foreach ($articles as $article) { ?>
        <article>
            <h2><?= htmlspecialchars($article->getTitre()) ?></h2>
            <p>
                <?= htmlspecialchars($article->getCategorie()) ?>
                · <?= htmlspecialchars($article->getDatePublication()->format('d.m.Y')) ?>
            </p>
            <p><?= htmlspecialchars($article->getContenu()) ?></p>
        </article>
    <?php } ?>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
