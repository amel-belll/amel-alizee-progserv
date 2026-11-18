<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';
require_once __DIR__ . '/../../utils/roles.php';

use Articles\ArticlesManager;

$racine = '../';
$page = 'admin';

// Page réservée aux admins
exigerAdmin($racine);

// Si un id est dans l'URL, on modifie cet article ; sinon, on en ajoute un nouveau
$idArticle = 0;

if (isset($_GET['id'])) {
    $idArticle = (int) $_GET['id'];
}

$modeModification = $idArticle > 0;

// Valeurs du formulaire (vides par défaut)
$titre = '';
$contenu = '';
$image = '';
$idCategorie = 0;

$erreurs = [];
$categories = [];
$articleIntrouvable = false;

try {
    $articlesManager = new ArticlesManager();

    // Liste des catégories pour la liste déroulante
    $categories = $articlesManager->getCategories();

    // En modification, on préremplit le formulaire avec l'article existant
    if ($modeModification && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $article = $articlesManager->getArticle($idArticle);

        if ($article === null) {
            $articleIntrouvable = true;
        } else {
            $titre = $article->getTitre();
            $contenu = $article->getContenu();
            $image = $article->getImageCouverture() ?? '';
            $idCategorie = $articlesManager->getIdCategorie($idArticle);
        }
    }

    // Le formulaire a été envoyé
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données du formulaire
        $titre = trim($_POST['titre']);
        $contenu = trim($_POST['contenu']);
        $image = trim($_POST['image']);
        $idCategorie = (int) $_POST['id_categorie'];

        // Une image vide est enregistrée comme null (pas d'image)
        $imageAEnregistrer = null;

        if ($image !== '') {
            $imageAEnregistrer = $image;
        }

        // Validation côté serveur : lance une exception si une donnée est invalide
        $articlesManager->validerArticle($titre, $contenu, $idCategorie);

        if ($modeModification) {
            $articlesManager->updateArticle($idArticle, $titre, $contenu, $imageAEnregistrer, $idCategorie);
            header('Location: articles.php?succes=modifie');
        } else {
            $articlesManager->addArticle($titre, $contenu, $imageAEnregistrer, $idCategorie, $_SESSION['utilisateur_id']);
            header('Location: articles.php?succes=ajoute');
        }

        exit();
    }
} catch (Exception $e) {
    // Le message de l'exception est le nom d'une clé de traduction
    $cle = $e->getMessage();

    if (isset($textes[$cle])) {
        $erreurs[] = $textes[$cle];
    } else {
        $erreurs[] = $textes['erreur_generale'];
    }
}

// Titre de la page selon le mode
if ($modeModification) {
    $titrePage = $textes['admin_article_modifier_titre'];
} else {
    $titrePage = $textes['admin_article_ajouter_titre'];
}

$title = $titrePage . ' | Zoomy';
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($langue) ?>">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre"><?= htmlspecialchars($titrePage) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <p>
                    <a href="articles.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['admin_retour_liste']) ?></a>
                </p>

                <?php if ($articleIntrouvable) { ?>
                    <p class="message erreur"><?= htmlspecialchars($textes['admin_article_introuvable']) ?></p>
                <?php } else { ?>

                    <!-- Liste des erreurs -->
                    <?php if (!empty($erreurs)) { ?>
                        <div class="message erreur">
                            <ul>
                                <?php foreach ($erreurs as $erreur) { ?>
                                    <li><?= htmlspecialchars($erreur) ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <form action="article-formulaire.php<?= $modeModification ? '?id=' . htmlspecialchars($idArticle) : '' ?>" method="POST" class="formulaire">
                        <label for="titre"><?= htmlspecialchars($textes['champ_titre']) ?></label>
                        <input type="text" id="titre" name="titre" class="aero-input" value="<?= htmlspecialchars($titre) ?>" required minlength="3" maxlength="255">

                        <label for="id_categorie"><?= htmlspecialchars($textes['champ_categorie']) ?></label>
                        <select id="id_categorie" name="id_categorie" class="aero-input" required>
                            <?php foreach ($categories as $categorie) { ?>
                                <option value="<?= htmlspecialchars($categorie['id']) ?>" <?= $categorie['id'] === $idCategorie ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($categorie['nom']) ?>
                                </option>
                            <?php } ?>
                        </select>

                        <label for="contenu"><?= htmlspecialchars($textes['champ_contenu']) ?></label>
                        <textarea id="contenu" name="contenu" class="aero-input" rows="10" required minlength="10"><?= htmlspecialchars($contenu) ?></textarea>

                        <label for="image"><?= htmlspecialchars($textes['champ_image']) ?></label>
                        <input type="text" id="image" name="image" class="aero-input" value="<?= htmlspecialchars($image) ?>">
                        <p class="texte-doux petit"><?= htmlspecialchars($textes['champ_image_aide']) ?></p>

                        <div class="quiz-actions">
                            <button type="submit" class="gel-btn green"><?= htmlspecialchars($textes['admin_enregistrer']) ?></button>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
