<?php
// Langues disponibles et langue par défaut
const LANGUES_DISPONIBLES = ['fr', 'en'];
const LANGUE_PAR_DEFAUT = 'fr';

// Durée de vie du cookie : 30 jours (en secondes)
const DUREE_COOKIE_LANGUE = 30 * 24 * 60 * 60;

// Langue par défaut
$langue = LANGUE_PAR_DEFAUT;

// Si un cookie de langue existe et qu'il est valide, on l'utilise
if (isset($_COOKIE['langue']) && in_array($_COOKIE['langue'], LANGUES_DISPONIBLES, true)) {
    $langue = $_COOKIE['langue'];
}

// Si la langue est choisie dans l'URL (?langue=en) et qu'elle est valide,
// on l'utilise et on la mémorise dans un cookie
if (isset($_GET['langue']) && in_array($_GET['langue'], LANGUES_DISPONIBLES, true)) {
    $langue = $_GET['langue'];
    setcookie('langue', $langue, time() + DUREE_COOKIE_LANGUE, '/');
}

// Chargement des textes de la langue choisie
$textes = require __DIR__ . '/../lang/' . $langue . '.php';
