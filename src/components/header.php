<?php

/** @var array $textes */

/** @var string $langue */

// Page actuelle, pour mettre le bon lien du menu en surbrillance.
// Chaque page définit $page avant d'inclure ce fichier (par exemple $page = 'articles';).
$page = $page ?? '';
$racine = $racine ?? './';

// Liens du sélecteur de langue : on garde les autres paramètres de l'adresse (par exemple ?id=3)
$parametres = $_GET;
$parametres['langue'] = 'fr';
$lienFr = '?' . http_build_query($parametres);
$parametres['langue'] = 'en';
$lienEn = '?' . http_build_query($parametres);
?>

<?php require __DIR__ . '/backdrop.php'; ?>

<header class="site-header">
    <div class="aero-window">
        <!-- Logo : globe + nom du site -->
        <a href="<?= htmlspecialchars($racine) ?>index.php" class="logo">
            <svg viewBox="0 0 32 32" class="icone">
                <defs>
                    <radialGradient id="globe" cx=".35" cy=".3" r=".8">
                        <stop offset="0" stop-color="#9ff3ff" />
                        <stop offset="1" stop-color="#0a6fb8" />
                    </radialGradient>
                </defs>
                <circle cx="16" cy="16" r="13" fill="url(#globe)" stroke="#0a5c9e" />
                <path d="M9 9c3 1 3 4 6 4s2 4 0 6-1 5 1 6M20 5c-1 3 2 4 4 5s3 3 2 5" fill="none" stroke="#58c43a" stroke-width="2.4" stroke-linecap="round" />
                <ellipse cx="13" cy="9" rx="7" ry="3.5" fill="#fff" opacity=".6" />
            </svg>
            <span class="nom">Zoomy</span>
        </a>

        <!-- Menu principal -->
        <nav class="site-nav">
            <a href="<?= htmlspecialchars($racine) ?>index.php" class="nav-pill <?= $page === 'accueil' ? 'active' : '' ?>"><?= htmlspecialchars($textes['menu_accueil']) ?></a>
            <a href="<?= htmlspecialchars($racine) ?>articles/" class="nav-pill <?= $page === 'articles' ? 'active' : '' ?>"><?= htmlspecialchars($textes['menu_articles']) ?></a>
            <a href="<?= htmlspecialchars($racine) ?>quiz/" class="nav-pill <?= $page === 'quiz' ? 'active' : '' ?>"><?= htmlspecialchars($textes['menu_quiz']) ?></a>
            <a href="<?= htmlspecialchars($racine) ?>musiques/" class="nav-pill <?= $page === 'musiques' ? 'active' : '' ?>"><?= htmlspecialchars($textes['menu_musiques']) ?></a>
            <a href="<?= htmlspecialchars($racine) ?>galerie/" class="nav-pill <?= $page === 'galerie' ? 'active' : '' ?>"><?= htmlspecialchars($textes['menu_galerie']) ?></a>
        </nav>

        <!-- Langue et compte -->
        <div class="header-actions">
            <div class="langues">
                <a href="<?= htmlspecialchars($lienFr) ?>" class="<?= $langue === 'fr' ? 'active' : '' ?>">fr</a>
                <a href="<?= htmlspecialchars($lienEn) ?>" class="<?= $langue === 'en' ? 'active' : '' ?>">en</a>
            </div>

            <?php if (isset($_SESSION['utilisateur_id'])) { ?>
                <!-- Connecté·e : on affiche le pseudo et la déconnexion -->
                <span class="chip"><?= htmlspecialchars($_SESSION['pseudo']) ?></span>
                <a href="<?= htmlspecialchars($racine) ?>auth/deconnexion.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['menu_deconnexion']) ?></a>
            <?php } else { ?>
                <!-- Pas connecté·e : connexion et inscription -->
                <a href="<?= htmlspecialchars($racine) ?>auth/connexion.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['menu_connexion']) ?></a>
                <a href="<?= htmlspecialchars($racine) ?>auth/inscription.php" class="gel-btn green sm"><?= htmlspecialchars($textes['menu_inscription']) ?></a>
            <?php } ?>
        </div>
    </div>
</header>
