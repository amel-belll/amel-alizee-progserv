<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';
require_once __DIR__ . '/../../utils/roles.php';

use Images\ImagesManager;

$racine = '../';
$page = 'admin';

// Page réservée aux admins
exigerAdmin($racine);

// Si un id est dans l'URL, on modifie cette image ; sinon, on en ajoute une nouvelle
$idImage = 0;

if (isset($_GET['id'])) {
    $idImage = (int) $_GET['id'];
}

$modeModification = $idImage > 0;

$titre = '';
$fichier = '';
$idCategorie = 0;

$erreurs = [];
$categories = [];
$introuvable = false;

try {
    $imagesManager = new ImagesManager();
    $categories = $imagesManager->getCategories();

    // En modification, on préremplit le formulaire
    if ($modeModification && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $image = $imagesManager->getImage($idImage);

        if ($image === null) {
            $introuvable = true;
        } else {
            $titre = $image->getTitre();
            $fichier = $image->getFichier();
            $idCategorie = $imagesManager->getIdCategorie($idImage);
        }
    }

    // Le formulaire a été envoyé
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre']);
        $fichier = trim($_POST['fichier']);
        $idCategorie = (int) $_POST['id_categorie'];

        // Validation côté serveur
        $imagesManager->validerImage($titre, $fichier, $idCategorie);

        if ($modeModification) {
            $imagesManager->updateImage($idImage, $titre, $fichier, $idCategorie);
            header('Location: images.php?succes=modifie');
        } else {
            $imagesManager->addImage($titre, $fichier, $idCategorie);
            header('Location: images.php?succes=ajoute');
        }

        exit();
    }
} catch (Exception $e) {
    $cle = $e->getMessage();

    if (isset($textes[$cle])) {
        $erreurs[] = $textes[$cle];
    } else {
        $erreurs[] = $textes['erreur_generale'];
    }
}

if ($modeModification) {
    $titrePage = $textes['admin_image_modifier_titre'];
} else {
    $titrePage = $textes['admin_image_ajouter_titre'];
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
                    <a href="images.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['admin_retour_liste']) ?></a>
                </p>

                <?php if ($introuvable) { ?>
                    <p class="message erreur"><?= htmlspecialchars($textes['admin_image_introuvable']) ?></p>
                <?php } else { ?>

                    <?php if (!empty($erreurs)) { ?>
                        <div class="message erreur">
                            <ul>
                                <?php foreach ($erreurs as $erreur) { ?>
                                    <li><?= htmlspecialchars($erreur) ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <form action="image-formulaire.php<?= $modeModification ? '?id=' . htmlspecialchars($idImage) : '' ?>" method="POST" class="formulaire">
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

                        <label for="fichier"><?= htmlspecialchars($textes['champ_fichier']) ?></label>
                        <input type="text" id="fichier" name="fichier" class="aero-input" value="<?= htmlspecialchars($fichier) ?>" required>
                        <p class="texte-doux petit"><?= htmlspecialchars($textes['champ_fichier_aide']) ?></p>

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
