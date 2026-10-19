<?php

namespace Articles;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;

class ArticlesManager {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getArticles(): array {
        // Définition de la requête SQL pour récupérer tous les articles avec leur catégorie
        $sql = "SELECT a.id, a.titre, a.contenu, a.image_couverture, a.date_publication, c.nom AS categorie
                FROM article a
                JOIN categorie c ON c.id = a.id_categorie
                ORDER BY a.date_publication DESC";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de tous les articles
        $lignes = $stmt->fetchAll();

        // Transformation de chaque ligne en objet Article
        $articles = [];

        foreach ($lignes as $ligne) {
            $articles[] = new Article(
                $ligne['id'],
                $ligne['titre'],
                $ligne['contenu'],
                $ligne['image_couverture'],
                new \DateTime($ligne['date_publication']),
                $ligne['categorie']
            );
        }

        // Retour de tous les articles
        return $articles;
    }
}
