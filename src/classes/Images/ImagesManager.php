<?php


namespace Images;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;
use Exception;

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

    public function getImage(int $id): ?Image {
        $sql = "SELECT i.id, i.titre, i.fichier, c.nom AS categorie
                FROM image i
                JOIN categorie c ON c.id = i.id_categorie
                WHERE i.id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $ligne = $stmt->fetch();

        if (!$ligne) {
            return null;
        }

        return new Image($ligne['id'], $ligne['titre'], $ligne['fichier'], $ligne['categorie']);
    }

    public function getCategories(): array {
        $stmt = $this->database->getPdo()->prepare("SELECT id, nom FROM categorie ORDER BY id");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getIdCategorie(int $idImage): int {
        $stmt = $this->database->getPdo()->prepare("SELECT id_categorie FROM image WHERE id = :id");
        $stmt->bindValue(':id', $idImage);
        $stmt->execute();

        $ligne = $stmt->fetch();

        if (!$ligne) {
            return 0;
        }

        return $ligne['id_categorie'];
    }

    // Vérifie les données d'une image (lance une exception avec une clé de traduction)
    public function validerImage(string $titre, string $fichier, int $idCategorie): void {
        if (strlen($titre) < 3 || strlen($titre) > 255) {
            throw new Exception('erreur_titre_longueur');
        }

        if ($fichier === '') {
            throw new Exception('erreur_fichier_obligatoire');
        }

        $categorieExiste = false;

        foreach ($this->getCategories() as $categorie) {
            if ($categorie['id'] === $idCategorie) {
                $categorieExiste = true;
            }
        }

        if (!$categorieExiste) {
            throw new Exception('erreur_categorie_invalide');
        }
    }

    public function addImage(string $titre, string $fichier, int $idCategorie): void {
        $sql = "INSERT INTO image (titre, fichier, id_categorie) VALUES (:titre, :fichier, :id_categorie)";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':titre', $titre);
        $stmt->bindValue(':fichier', $fichier);
        $stmt->bindValue(':id_categorie', $idCategorie);
        $stmt->execute();
    }

    public function updateImage(int $id, string $titre, string $fichier, int $idCategorie): void {
        $sql = "UPDATE image SET titre = :titre, fichier = :fichier, id_categorie = :id_categorie WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':titre', $titre);
        $stmt->bindValue(':fichier', $fichier);
        $stmt->bindValue(':id_categorie', $idCategorie);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    // Supprime une image et ses favoris (à cause de la clé étrangère)
    public function removeImage(int $id): void {
        $pdo = $this->database->getPdo();

        $stmt = $pdo->prepare("DELETE FROM image_favorite WHERE id_image = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM image WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
