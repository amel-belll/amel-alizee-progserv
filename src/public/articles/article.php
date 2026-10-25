<?php
require __DIR__ . '/../../utils/autoloader.php';

use Articles\ArticlesManager;

$racine = '../';

// Création d'une instance de ArticlesManager
$articlesManager = new ArticlesManager();

// Par défaut, aucun article n'est trouvé
$article = null;

// Vérification si l'ID de l'article est passé dans l'URL
if (isset($_GET["id"])) {
    // Récupération de l'ID de l'article de la superglobale `$_GET`, transformé en nombre entier
    $articleId = (int) $_GET["id"];

    // Récupération de l'article correspondant à l'ID
    $article = $articlesManager->getArticle($articleId);
}

// Titre de l'onglet du navigateur
if ($article !== null) {
    $title = $article->getTitre() . ' | Zoomy';
} else {
    $title = 'Article introuvable | Zoomy';
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <p><a href="../index.php">Accueil</a> > <a href="index.php">Articles</a></p>

    <?php if ($article !== null) { ?>
        <article>
            <h1><?= htmlspecialchars($article->getTitre()) ?></h1>
            <p>
                <?= htmlspecialchars($article->getCategorie()) ?>
                · <?= htmlspecialchars($article->getDatePublication()->format('d.m.Y')) ?>
                · par <?= htmlspecialchars($article->getAuteur()) ?>
            </p>
            <p><?= htmlspecialchars($article->getContenu()) ?></p>
        </article>
    <?php } else { ?>
        <h1>Article introuvable</h1>
        <p>Cet article n'existe pas ou a été supprimé.</p>
    <?php } ?>

    <p><a href="index.php">← Retour aux articles</a></p>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
