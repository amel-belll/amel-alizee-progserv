<!-- Décor de fond Frutiger Aero (repris de Backdrop.tsx de la maquette) -->
<div class="decor" aria-hidden="true">
    <!-- Ciel -->
    <div class="ciel"></div>

    <!-- Rayons de lumière et lueur -->
    <div class="rayons"></div>
    <div class="lueur"></div>

    <!-- Nuages -->
    <svg viewBox="0 0 200 80" style="left: 8%; top: 12%; width: 260px;">
        <g fill="#fff" opacity=".92"><circle cx="60" cy="50" r="28" /><circle cx="100" cy="36" r="34" /><circle cx="140" cy="50" r="26" /><rect x="40" y="50" width="120" height="26" rx="13" /></g>
    </svg>
    <svg viewBox="0 0 200 80" style="left: 62%; top: 6%; width: 338px;">
        <g fill="#fff" opacity=".92"><circle cx="60" cy="50" r="28" /><circle cx="100" cy="36" r="34" /><circle cx="140" cy="50" r="26" /><rect x="40" y="50" width="120" height="26" rx="13" /></g>
    </svg>
    <svg viewBox="0 0 200 80" style="left: 80%; top: 26%; width: 234px;">
        <g fill="#fff" opacity=".92"><circle cx="60" cy="50" r="28" /><circle cx="100" cy="36" r="34" /><circle cx="140" cy="50" r="26" /><rect x="40" y="50" width="120" height="26" rx="13" /></g>
    </svg>
    <svg viewBox="0 0 200 80" style="left: 30%; top: 30%; width: 208px;">
        <g fill="#fff" opacity=".92"><circle cx="60" cy="50" r="28" /><circle cx="100" cy="36" r="34" /><circle cx="140" cy="50" r="26" /><rect x="40" y="50" width="120" height="26" rx="13" /></g>
    </svg>

    <!-- Planète Terre -->
    <svg viewBox="0 0 200 200" class="terre">
        <defs>
            <radialGradient id="ea" cx=".38" cy=".32" r=".75"><stop offset="0" stop-color="#8fe9ff" /><stop offset=".6" stop-color="#1683d6" /><stop offset="1" stop-color="#063a7a" /></radialGradient>
            <clipPath id="ec"><circle cx="100" cy="100" r="90" /></clipPath>
        </defs>
        <circle cx="100" cy="100" r="90" fill="url(#ea)" />
        <g clip-path="url(#ec)" fill="#5fcf3a" opacity=".9">
            <path d="M40 60c20-10 40 0 45 18s-10 25-5 40-20 20-30 8-25-20-20-40 0-20 10-26z" />
            <path d="M120 40c15 0 35 10 40 25s-10 20-20 18-15 10-25 5-10-20-5-30 0-18 10-18z" />
            <path d="M130 120c15-5 30 5 28 20s-20 25-30 20-12-35 2-40z" />
        </g>
        <ellipse cx="72" cy="58" rx="42" ry="22" fill="#fff" opacity=".45" />
    </svg>

    <!-- Collines -->
    <svg viewBox="0 0 1440 400" preserveAspectRatio="none" class="collines">
        <defs>
            <linearGradient id="h1" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#b6f25a" /><stop offset="1" stop-color="#2f9a14" /></linearGradient>
            <linearGradient id="h2" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#8fdc3a" /><stop offset="1" stop-color="#1f7a0e" /></linearGradient>
        </defs>
        <path d="M0 220 Q 360 80 760 190 T 1440 150 V400 H0z" fill="url(#h1)" />
        <path d="M0 300 Q 500 170 980 280 T 1440 260 V400 H0z" fill="url(#h2)" />
        <path d="M0 220 Q 360 80 760 190 T 1440 150" fill="none" stroke="#eaffc0" stroke-width="4" opacity=".7" />
    </svg>

    <!-- Poissons -->
    <svg viewBox="0 0 60 30" class="poisson" style="left: 6%; top: 58%;">
        <path d="M5 15 Q 25 0 45 15 Q 25 30 5 15z" fill="#ff9a2a" stroke="#fff" stroke-width="1.5" /><path d="M45 15 L58 5 L58 25z" fill="#ff9a2a" /><circle cx="14" cy="13" r="2" fill="#063a5a" /><ellipse cx="22" cy="10" rx="8" ry="2.5" fill="#fff" opacity=".6" />
    </svg>
    <svg viewBox="0 0 60 30" class="poisson" style="left: 14%; top: 64%; animation-delay: -2s;">
        <path d="M5 15 Q 25 0 45 15 Q 25 30 5 15z" fill="#2ad0e6" stroke="#fff" stroke-width="1.5" /><path d="M45 15 L58 5 L58 25z" fill="#2ad0e6" /><circle cx="14" cy="13" r="2" fill="#063a5a" /><ellipse cx="22" cy="10" rx="8" ry="2.5" fill="#fff" opacity=".6" />
    </svg>
    <svg viewBox="0 0 60 30" class="poisson" style="left: 88%; top: 70%; animation-delay: -4s;">
        <path d="M5 15 Q 25 0 45 15 Q 25 30 5 15z" fill="#ffcf3a" stroke="#fff" stroke-width="1.5" /><path d="M45 15 L58 5 L58 25z" fill="#ffcf3a" /><circle cx="14" cy="13" r="2" fill="#063a5a" /><ellipse cx="22" cy="10" rx="8" ry="2.5" fill="#fff" opacity=".6" />
    </svg>

    <!-- Papillons -->
    <svg viewBox="0 0 40 30" class="papillon" style="left: 20%; top: 44%;">
        <path d="M20 15 C 8 0 0 6 4 14 C 0 22 10 28 20 16z M20 15 C 32 0 40 6 36 14 C 40 22 30 28 20 16z" fill="#1aa3ef" stroke="#063a7a" /><ellipse cx="11" cy="9" rx="4" ry="2" fill="#bdf" />
    </svg>
    <svg viewBox="0 0 40 30" class="papillon" style="left: 72%; top: 50%; animation-delay: -1.6s;">
        <path d="M20 15 C 8 0 0 6 4 14 C 0 22 10 28 20 16z M20 15 C 32 0 40 6 36 14 C 40 22 30 28 20 16z" fill="#1aa3ef" stroke="#063a7a" /><ellipse cx="11" cy="9" rx="4" ry="2" fill="#bdf" />
    </svg>
    <svg viewBox="0 0 40 30" class="papillon" style="left: 48%; top: 18%; animation-delay: -3.2s;">
        <path d="M20 15 C 8 0 0 6 4 14 C 0 22 10 28 20 16z M20 15 C 32 0 40 6 36 14 C 40 22 30 28 20 16z" fill="#1aa3ef" stroke="#063a7a" /><ellipse cx="11" cy="9" rx="4" ry="2" fill="#bdf" />
    </svg>

    <!-- Bulles qui montent : 16 bulles avec une position, une taille et une vitesse différentes -->
    <?php for ($i = 0; $i < 16; $i++) { ?>
        <span class="bulle" style="
            left: <?= ($i * 37) % 100 ?>%;
            width: <?= 14 + (($i * 13) % 46) ?>px;
            height: <?= 14 + (($i * 13) % 46) ?>px;
            animation-duration: <?= 14 + (($i * 7) % 16) ?>s;
            animation-delay: -<?= $i * 2.3 ?>s;"></span>
    <?php } ?>
</div>
