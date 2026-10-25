<?php
require __DIR__ . '/../../utils/autoloader.php';

use Articles\ArticlesManager;

$title = 'Articles | Zoomy';
$racine = '../';

// Liste des catégories autorisées pour le filtre
const CATEGORIES = ['Mode', 'Voyages', 'Design'];

// Création d'une instance de ArticlesManager
$articlesManager = new ArticlesManager();

// Par défaut, aucune catégorie n'est choisie
$categorieChoisie = null;

// Vérification si une catégorie valide est passée dans l'URL
if (isset($_GET["categorie"]) && in_array($_GET["categorie"], CATEGORIES)) {
    $categorieChoisie = $_GET["categorie"];
}

// Récupération des articles : ceux de la catégorie choisie, ou tous les articles
if ($categorieChoisie !== null) {
    $articles = $articlesManager->getArticlesParCategorie($categorieChoisie);
} else {
    $articles = $articlesManager->getArticles();
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <h1>Articles</h1>

    <p><a href="../index.php">Accueil</a> > Articles</p>

    <nav>
        <a href="index.php">Tous</a>
        <?php foreach (CATEGORIES as $categorie) { ?>
            <a href="index.php?categorie=<?= htmlspecialchars($categorie) ?>"><?= htmlspecialchars($categorie) ?></a>
        <?php } ?>
    </nav>

    <?php if (empty($articles)) { ?>
        <p>Aucun article dans cette catégorie pour le moment.</p>
    <?php } ?>

    <?php foreach ($articles as $article) { ?>
        <article>
            <h2>
                <a href="article.php?id=<?= htmlspecialchars($article->getId()) ?>">
                    <?= htmlspecialchars($article->getTitre()) ?>
                </a>
            </h2>
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
