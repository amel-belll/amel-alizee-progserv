<?php
require __DIR__ . '/../../utils/autoloader.php';

use Articles\ArticlesManager;

$racine = '../';
$page = 'articles';

// Par défaut : pas d'erreur et aucun article trouvé
$erreur = null;
$article = null;

try {
    // Création d'une instance de ArticlesManager (connexion à la base)
    $articlesManager = new ArticlesManager();

    // Vérification si l'ID de l'article est passé dans l'URL
    if (isset($_GET["id"])) {
        // Récupération de l'ID de l'article de la superglobale `$_GET`, transformé en nombre entier
        $articleId = (int) $_GET["id"];

        // Récupération de l'article correspondant à l'ID
        $article = $articlesManager->getArticle($articleId);
    }
} catch (Exception $e) {
    // Si la base ne répond pas, on garde le message pour l'afficher
    $erreur = $e->getMessage();
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

    <main>
        <section class="aero-window article-detail">
            <div class="aero-titlebar">
                <span class="titre"><?= $article !== null ? htmlspecialchars($article->getTitre()) : 'Article introuvable' ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <?php if ($article !== null) { ?>
                <?php if ($article->getImageCouverture() !== null) { ?>
                    <img src="../images/articles/<?= htmlspecialchars($article->getImageCouverture()) ?>" alt="<?= htmlspecialchars($article->getTitre()) ?>" class="grande-image">
                <?php } ?>

                <article>
                    <a href="index.php" class="gel-btn ghost sm">← Retour</a>

                    <p class="infos">
                        <span class="chip"><?= htmlspecialchars($article->getCategorie()) ?></span>
                        <?= htmlspecialchars($article->getDatePublication()->format('d.m.Y')) ?>
                        · par <b><?= htmlspecialchars($article->getAuteur()) ?></b>
                    </p>

                    <h1><?= htmlspecialchars($article->getTitre()) ?></h1>

                    <div class="contenu">
                        <p><?= htmlspecialchars($article->getContenu()) ?></p>
                    </div>
                </article>
            <?php } else { ?>
                <article>
                    <a href="index.php" class="gel-btn ghost sm">← Retour</a>
                    <h1>Article introuvable</h1>

                    <?php if ($erreur !== null) { ?>
                        <!-- Message si la base de données ne répond pas -->
                        <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                    <?php } else { ?>
                        <p class="message">Cet article n'existe pas ou a été supprimé.</p>
                    <?php } ?>
                </article>
            <?php } ?>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
