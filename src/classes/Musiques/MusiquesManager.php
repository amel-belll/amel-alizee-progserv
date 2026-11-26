<?php

namespace Musiques;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;
use Exception;

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

    // Récupère une musique à partir de son id (ou null si elle n'existe pas)
    public function getMusique(int $id): ?Musique {
        $sql = "SELECT id, titre, artiste, genre, pochette, lien
                FROM musique
                WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $ligne = $stmt->fetch();

        if (!$ligne) {
            return null;
        }

        return new Musique(
            $ligne['id'],
            $ligne['titre'],
            $ligne['artiste'],
            $ligne['genre'],
            $ligne['pochette'],
            $ligne['lien']
        );
    }

    // Vérifie les données d'une musique (lance une exception avec une clé de traduction)
    public function validerMusique(string $titre, string $artiste, string $lien): void {
        if (strlen($titre) < 3 || strlen($titre) > 255) {
            throw new Exception('erreur_titre_longueur');
        }

        if (strlen($artiste) < 1 || strlen($artiste) > 255) {
            throw new Exception('erreur_artiste_longueur');
        }

        if ($lien === '') {
            throw new Exception('erreur_lien_obligatoire');
        }
    }

    public function addMusique(string $titre, string $artiste, ?string $genre, ?string $pochette, string $lien): void {
        $sql = "INSERT INTO musique (titre, artiste, genre, pochette, lien)
                VALUES (:titre, :artiste, :genre, :pochette, :lien)";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':titre', $titre);
        $stmt->bindValue(':artiste', $artiste);
        $stmt->bindValue(':genre', $genre);
        $stmt->bindValue(':pochette', $pochette);
        $stmt->bindValue(':lien', $lien);
        $stmt->execute();
    }

    public function updateMusique(int $id, string $titre, string $artiste, ?string $genre, ?string $pochette, string $lien): void {
        $sql = "UPDATE musique
                SET titre = :titre, artiste = :artiste, genre = :genre, pochette = :pochette, lien = :lien
                WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':titre', $titre);
        $stmt->bindValue(':artiste', $artiste);
        $stmt->bindValue(':genre', $genre);
        $stmt->bindValue(':pochette', $pochette);
        $stmt->bindValue(':lien', $lien);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    // Supprime une musique et ses favoris (à cause de la clé étrangère)
    public function removeMusique(int $id): void {
        $pdo = $this->database->getPdo();

        $stmt = $pdo->prepare("DELETE FROM musique_favorite WHERE id_musique = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM musique WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
