<?php
// Page actuelle, pour mettre le bon lien du menu en surbrillance.
// Chaque page définit $page avant d'inclure ce fichier (par exemple $page = 'articles';).
$page = $page ?? '';
$racine = $racine ?? './';
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
            <a href="<?= htmlspecialchars($racine) ?>index.php" class="nav-pill <?= $page === 'accueil' ? 'active' : '' ?>">Accueil</a>
            <a href="<?= htmlspecialchars($racine) ?>articles/" class="nav-pill <?= $page === 'articles' ? 'active' : '' ?>">Articles</a>
            <a href="<?= htmlspecialchars($racine) ?>quiz/" class="nav-pill <?= $page === 'quiz' ? 'active' : '' ?>">Quiz</a>
            <a href="<?= htmlspecialchars($racine) ?>musiques/" class="nav-pill <?= $page === 'musiques' ? 'active' : '' ?>">Musiques</a>
            <a href="<?= htmlspecialchars($racine) ?>galerie/" class="nav-pill <?= $page === 'galerie' ? 'active' : '' ?>">Galerie</a>
        </nav>

        <!-- Langue et compte (seront branchés aux séances 8 et 9) -->
        <div class="header-actions">
            <div class="langues">
                <a href="#" class="active">fr</a>
                <a href="#">en</a>
            </div>
            <a href="#" class="gel-btn ghost sm">Connexion</a>
            <a href="#" class="gel-btn green sm">Inscription</a>
        </div>
    </div>
</header>
