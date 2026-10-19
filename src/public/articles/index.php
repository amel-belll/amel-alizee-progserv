<?php
require __DIR__ . '/../../utils/autoloader.php';

use Articles\ArticlesManager;

// Création d'une instance de ArticlesManager
$articlesManager = new ArticlesManager();

// Récupération de tous les articles
$articles = $articlesManager->getArticles();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles | Nom du site à définir</title>
</head>

<body>
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

    <footer>
        <p>Projet réalisé dans le cadre du cours ProgServ2 à la HEIG-VD.</p>
    </footer>
</body>

</html>
