<?php

namespace Images;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;

class ImagesManager {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getImages(): array {
        // Définition de la requête SQL pour récupérer toutes les images avec leur catégorie
        $sql = "SELECT i.id, i.titre, i.fichier, c.nom AS categorie
                FROM image i
                JOIN categorie c ON c.id = i.id_categorie
                ORDER BY i.id DESC";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de toutes les images
        $lignes = $stmt->fetchAll();

        // Transformation de chaque ligne en objet Image
        $images = [];

        foreach ($lignes as $ligne) {
            $images[] = new Image(
                $ligne['id'],
                $ligne['titre'],
                $ligne['fichier'],
                $ligne['categorie']
            );
        }

        // Retour de toutes les images
        return $images;
    }
}
