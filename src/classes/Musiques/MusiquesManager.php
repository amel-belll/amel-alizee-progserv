<?php

namespace Musiques;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;

class MusiquesManager {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getMusiques(): array {
        // Définition de la requête SQL pour récupérer toutes les musiques
        $sql = "SELECT id, titre, artiste, genre, pochette, lien
                FROM musique
                ORDER BY id DESC";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de toutes les musiques
        $lignes = $stmt->fetchAll();

        // Transformation de chaque ligne en objet Musique
        $musiques = [];

        foreach ($lignes as $ligne) {
            $musiques[] = new Musique(
                $ligne['id'],
                $ligne['titre'],
                $ligne['artiste'],
                $ligne['genre'],
                $ligne['pochette'],
                $ligne['lien']
            );
        }

        // Retour de toutes les musiques
        return $musiques;
    }
}
