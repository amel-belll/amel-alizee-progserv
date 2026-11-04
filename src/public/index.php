<?php
require __DIR__ . '/../utils/autoloader.php';
require_once __DIR__ . '/../utils/langue.php';

use Articles\ArticlesManager;
use Musiques\MusiquesManager;
use Quiz\QuizManager;

$title = $textes['onglet_accueil'];
$racine = './';
$page = 'accueil';

// Par défaut : pas d'erreur, pas d'articles, pas de musique ni de quiz à la une
$erreur = null;
$derniersArticles = [];
$musiqueUne = null;
$quizUne = null;

try {
    // Création des Managers (connexion à la base)
    $articlesManager = new ArticlesManager();
    $musiquesManager = new MusiquesManager();
    $quizManager = new QuizManager();

    // Récupération des 3 derniers articles
    $derniersArticles = $articlesManager->getDerniersArticles(3);

    // Musique à la une : la plus récente (la première de la liste), s'il y en a une
    $musiques = $musiquesManager->getMusiques();

    if (!empty($musiques)) {
        $musiqueUne = $musiques[0];
    }

    // Quiz à la une : le plus récent, s'il y en a un
    $quizListe = $quizManager->getQuiz();

    if (!empty($quizListe)) {
        $quizUne = $quizListe[0];
    }
} catch (Exception $e) {
    // Si la base ne répond pas, on garde le message pour l'afficher
    $erreur = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($langue) ?>">

<?php require __DIR__ . '/../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../components/header.php'; ?>

    <main class="accueil">
        <!-- Fenêtre de bienvenue -->
        <section class="aero-window bienvenue">
            <div class="aero-titlebar">
                <span class="titre"><?= htmlspecialchars($textes['accueil_bienvenue_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps bienvenue-corps">
                <div>
                    <p class="chip"><?= htmlspecialchars($textes['accueil_chip']) ?></p>
                    <h1><?= htmlspecialchars($textes['accueil_titre_ligne1']) ?><br><span class="vert"><?= htmlspecialchars($textes['accueil_titre_ligne2']) ?></span></h1>
                    <p class="texte-intro"><?= htmlspecialchars($textes['accueil_intro']) ?></p>
                    <a href="articles/" class="gel-btn"><?= htmlspecialchars($textes['accueil_lire_articles']) ?></a>
                    <a href="quiz/" class="gel-btn green"><?= htmlspecialchars($textes['accueil_trouver_aesthetic']) ?></a>
                </div>

                <!-- Les 4 icônes décoratives de la maquette -->
                <div class="icones-bienvenue" aria-hidden="true">
                    <img src="images/icone-utilisateur.png" alt="">
                    <img src="images/icone-objet.png" alt="">
                    <img src="images/icone-3.png" alt="">
                    <img src="images/icone-4.png" alt="">
                </div>
            </div>
        </section>
        <!-- Colonne de gauche : derniers articles, puis quiz à la une -->
        <div class="colonne-gauche">
            <section class="aero-window">
                <div class="aero-titlebar">
                    <span class="titre"><?= htmlspecialchars($textes['accueil_derniers_articles']) ?></span>
                    <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
                </div>

                <div class="fenetre-corps">
                    <?php if ($erreur !== null) { ?>
                        <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                    <?php } elseif (empty($derniersArticles)) { ?>
                        <p class="message"><?= htmlspecialchars($textes['accueil_aucun_article']) ?></p>
                    <?php } ?>

                    <div class="grille">
                        <?php foreach ($derniersArticles as $article) { ?>
                            <a href="articles/article.php?id=<?= htmlspecialchars($article->getId()) ?>" class="carte">
                                <div class="carte-image">
                                    <?php if ($article->getImageCouverture() !== null) { ?>
                                        <img src="images/articles/<?= htmlspecialchars($article->getImageCouverture()) ?>" alt="<?= htmlspecialchars($article->getTitre()) ?>">
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

            <?php if ($quizUne !== null) { ?>
                <section class="aero-window">
                    <div class="aero-titlebar">
                        <span class="titre"><?= htmlspecialchars($textes['accueil_quiz_une']) ?></span>
                        <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
                    </div>

                    <div class="fenetre-corps">
                        <h3><?= htmlspecialchars($quizUne->getTitre()) ?></h3>

                        <?php if ($quizUne->getDescription() !== null) { ?>
                            <p class="texte-doux"><?= htmlspecialchars($quizUne->getDescription()) ?></p>
                        <?php } ?>

                        <a href="quiz/jouer.php?id=<?= htmlspecialchars($quizUne->getId()) ?>" class="gel-btn green sm"><?= htmlspecialchars($textes['accueil_faire_quiz']) ?></a>
                    </div>
                </section>
            <?php } ?>
        </div>

        <!-- Colonne de droite : musique à la une -->
        <div class="colonne-une">
            <?php if ($musiqueUne !== null) { ?>
                <section class="aero-window">
                    <div class="aero-titlebar">
                        <span class="titre"><?= htmlspecialchars($textes['accueil_musique_une']) ?></span>
                        <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
                    </div>

                    <div class="fenetre-corps">
                        <div class="baladeur">
                            <img src="images/baladeur.png" alt="">
                            <iframe
                                class="ecran-baladeur"
                                src="https://www.youtube.com/embed/<?= htmlspecialchars($musiqueUne->getLien()) ?>"
                                title="<?= htmlspecialchars($musiqueUne->getTitre()) ?>"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <p><b><?= htmlspecialchars($musiqueUne->getTitre()) ?></b></p>
                        <p class="texte-doux"><?= htmlspecialchars($musiqueUne->getArtiste()) ?></p>
                    </div>
                </section>
            <?php } ?>
        </div>
    </main>

    <?php require __DIR__ . '/../components/footer.php'; ?>
</body>

</html>
