<?php
// On reprend la session en cours
session_start();

// On vide toutes les informations de la session
$_SESSION = [];

// On détruit la session
session_destroy();

// Retour à l'accueil
header('Location: ../index.php');
exit();
