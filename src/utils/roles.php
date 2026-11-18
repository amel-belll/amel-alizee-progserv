<?php

// Redirige vers la page de connexion si personne n'est connecté.
// $racine = chemin vers src/public/ depuis la page (par exemple './' ou '../').
function exigerConnexion(string $racine): void {
    if (!isset($_SESSION['utilisateur_id'])) {
        header('Location: ' . $racine . 'auth/connexion.php');
        exit;
    }
}

// Redirige vers l'accueil si l'utilisateur n'est pas admin.
function exigerAdmin(string $racine): void {
    // D'abord, il faut être connecté
    exigerConnexion($racine);

    if ($_SESSION['role'] !== 'admin') {
        header('Location: ' . $racine . 'index.php');
        exit;
    }
}
