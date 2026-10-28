<?php
require __DIR__ . '/../../utils/autoloader.php';

use Articles\ArticlesManager;

$title = 'Articles | Zoomy';
$racine = '../';
$page = 'articles';

// Liste des catégories autorisées pour le filtre
const CATEGORIES = ['Mode', 'Voyages', 'Design'];

// Par défaut, aucune catégorie n'est choisie
$categorieChoisie = null;

// Vérification si une catégorie valide est passée dans l'URL
if (isset($_GET["categorie"]) && in_array($_GET["categorie"], CATEGORIES)) {
    $categorieChoisie = $_GET["categorie"];
}

// Par défaut : pas d'erreur et aucun article
$erreur = null;
$articles = [];

try {
    // Création d'une instance de ArticlesManager (connexion à la base)
    $articlesManager = new ArticlesManager();

    // Récupération des articles : ceux de la catégorie choisie, ou tous les articles
    if ($categorieChoisie !== null) {
        $articles = $articlesManager->getArticlesParCategorie($categorieChoisie);
    } else {
        $articles = $articlesManager->getArticles();
    }
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
                <span class="titre">Articles</span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <!-- Filtres : le bouton de la catégorie choisie est bleu, les autres sont clairs -->
                <div class="filtres">
                    <a href="index.php" class="gel-btn sm <?= $categorieChoisie === null ? '' : 'ghost' ?>">Tous</a>
                    <?php foreach (CATEGORIES as $categorie) { ?>
                        <a href="index.php?categorie=<?= htmlspecialchars($categorie) ?>" class="gel-btn sm <?= $categorieChoisie === $categorie ? '' : 'ghost' ?>"><?= htmlspecialchars($categorie) ?></a>
                    <?php } ?>
                </div>

                <?php if ($erreur !== null) { ?>
                    <!-- Message si la base de données ne répond pas -->
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } elseif (empty($articles)) { ?>
                    <p class="message">Aucun article dans cette catégorie pour le moment.</p>
                <?php } ?>

                <!-- Grille de cartes -->
                <div class="grille">
                    <?php foreach ($articles as $article) { ?>
                        <a href="article.php?id=<?= htmlspecialchars($article->getId()) ?>" class="carte">
                            <div class="carte-image">
                                <?php if ($article->getImageCouverture() !== null) { ?>
                                    <img src="../images/articles/<?= htmlspecialchars($article->getImageCouverture()) ?>" alt="<?= htmlspecialchars($article->getTitre()) ?>">
                                <?php } ?>
                            </div>
                            <div class="carte-corps">
                                <div class="carte-infos">
                                    <span class="chip"><?= htmlspecialchars($article->getCategorie()) ?></span>
                                    <?= htmlspecialchars($article->getDatePublication()->format('d.m.Y')) ?>
                                </div>
                                <h3><?= htmlspecialchars($article->getTitre()) ?></h3>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
