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
        // Définition de la requête SQL pour récupérer tous les articles avec leur catégorie et leur auteur
        $sql = "SELECT a.id, a.titre, a.contenu, a.image_couverture, a.date_publication, c.nom AS categorie, u.pseudo AS auteur
                FROM article a
                JOIN categorie c ON c.id = a.id_categorie
                JOIN utilisateur u ON u.id = a.id_auteur
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
                $ligne['categorie'],
                $ligne['auteur']
            );
        }

        // Retour de tous les articles
        return $articles;
    }
    public function getArticle(int $id): ?Article {
        // Définition de la requête SQL pour récupérer un article précis avec sa catégorie et son auteur
        $sql = "SELECT a.id, a.titre, a.contenu, a.image_couverture, a.date_publication, c.nom AS categorie, u.pseudo AS auteur
                FROM article a
                JOIN categorie c ON c.id = a.id_categorie
                JOIN utilisateur u ON u.id = a.id_auteur
                WHERE a.id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':id', $id);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération d'une seule ligne
        $ligne = $stmt->fetch();

        // Si aucun article ne correspond, on renvoie null
        if (!$ligne) {
            return null;
        }

        // Transformation de la ligne en objet Article
        return new Article(
            $ligne['id'],
            $ligne['titre'],
            $ligne['contenu'],
            $ligne['image_couverture'],
            new \DateTime($ligne['date_publication']),
            $ligne['categorie'],
            $ligne['auteur']
        );
    }
    public function getArticlesParCategorie(string $categorie): array {
        // Définition de la requête SQL pour récupérer les articles d'une catégorie
        $sql = "SELECT a.id, a.titre, a.contenu, a.image_couverture, a.date_publication, c.nom AS categorie, u.pseudo AS auteur
                FROM article a
                JOIN categorie c ON c.id = a.id_categorie
                JOIN utilisateur u ON u.id = a.id_auteur
                WHERE c.nom = :categorie
                ORDER BY a.date_publication DESC";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':categorie', $categorie);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération des articles
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
                $ligne['categorie'],
                $ligne['auteur']
            );
        }

        // Retour des articles de la catégorie
        return $articles;
    }
}
