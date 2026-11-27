<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';
require_once __DIR__ . '/../../utils/roles.php';

use Musiques\MusiquesManager;

$racine = '../';
$page = 'admin';

// Page réservée aux admins
exigerAdmin($racine);

// Si un id est dans l'URL, on modifie cette musique ; sinon, on en ajoute une nouvelle
$idMusique = 0;

if (isset($_GET['id'])) {
    $idMusique = (int) $_GET['id'];
}

$modeModification = $idMusique > 0;

$titre = '';
$artiste = '';
$genre = '';
$pochette = '';
$lien = '';

$erreurs = [];
$introuvable = false;

try {
    $musiquesManager = new MusiquesManager();

    // En modification, on préremplit le formulaire
    if ($modeModification && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $musique = $musiquesManager->getMusique($idMusique);

        if ($musique === null) {
            $introuvable = true;
        } else {
            $titre = $musique->getTitre();
            $artiste = $musique->getArtiste();
            $genre = $musique->getGenre() ?? '';
            $pochette = $musique->getPochette() ?? '';
            $lien = $musique->getLien();
        }
    }

    // Le formulaire a été envoyé
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre']);
        $artiste = trim($_POST['artiste']);
        $genre = trim($_POST['genre']);
        $pochette = trim($_POST['pochette']);
        $lien = trim($_POST['lien']);

        // Validation côté serveur
        $musiquesManager->validerMusique($titre, $artiste, $lien);

        // Le genre et la pochette sont facultatifs : un champ vide devient null
        $genreEnregistre = null;

        if ($genre !== '') {
            $genreEnregistre = $genre;
        }

        $pochetteEnregistree = null;

        if ($pochette !== '') {
            $pochetteEnregistree = $pochette;
        }

        if ($modeModification) {
            $musiquesManager->updateMusique($idMusique, $titre, $artiste, $genreEnregistre, $pochetteEnregistree, $lien);
            header('Location: musiques.php?succes=modifie');
        } else {
            $musiquesManager->addMusique($titre, $artiste, $genreEnregistre, $pochetteEnregistree, $lien);
            header('Location: musiques.php?succes=ajoute');
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
    $titrePage = $textes['admin_musique_modifier_titre'];
} else {
    $titrePage = $textes['admin_musique_ajouter_titre'];
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
                    <a href="musiques.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['admin_retour_liste']) ?></a>
                </p>

                <?php if ($introuvable) { ?>
                    <p class="message erreur"><?= htmlspecialchars($textes['admin_musique_introuvable']) ?></p>
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

                    <form action="musique-formulaire.php<?= $modeModification ? '?id=' . htmlspecialchars($idMusique) : '' ?>" method="POST" class="formulaire">
                        <label for="titre"><?= htmlspecialchars($textes['champ_titre']) ?></label>
                        <input type="text" id="titre" name="titre" class="aero-input" value="<?= htmlspecialchars($titre) ?>" required minlength="3" maxlength="255">

                        <label for="artiste"><?= htmlspecialchars($textes['champ_artiste']) ?></label>
                        <input type="text" id="artiste" name="artiste" class="aero-input" value="<?= htmlspecialchars($artiste) ?>" required maxlength="255">

                        <label for="genre"><?= htmlspecialchars($textes['champ_genre']) ?></label>
                        <input type="text" id="genre" name="genre" class="aero-input" value="<?= htmlspecialchars($genre) ?>" maxlength="100">

                        <label for="pochette"><?= htmlspecialchars($textes['champ_pochette']) ?></label>
                        <input type="text" id="pochette" name="pochette" class="aero-input" value="<?= htmlspecialchars($pochette) ?>" maxlength="255">
                        <p class="texte-doux petit"><?= htmlspecialchars($textes['champ_pochette_aide']) ?></p>

                        <label for="lien"><?= htmlspecialchars($textes['champ_lien']) ?></label>
                        <input type="text" id="lien" name="lien" class="aero-input" value="<?= htmlspecialchars($lien) ?>" required maxlength="255">
                        <p class="texte-doux petit"><?= htmlspecialchars($textes['champ_lien_aide']) ?></p>

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
