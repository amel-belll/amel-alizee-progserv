<?php

namespace Articles;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;
use Exception;

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
    public function getDerniersArticles(int $nombre): array {
        // Définition de la requête SQL pour récupérer les articles les plus récents
        $sql = "SELECT a.id, a.titre, a.contenu, a.image_couverture, a.date_publication, c.nom AS categorie, u.pseudo AS auteur
                FROM article a
                JOIN categorie c ON c.id = a.id_categorie
                JOIN utilisateur u ON u.id = a.id_auteur
                ORDER BY a.date_publication DESC
                LIMIT :nombre";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre (en précisant que c'est un nombre entier)
        $stmt->bindValue(':nombre', $nombre, \PDO::PARAM_INT);

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

        // Retour des articles
        return $articles;
    }
    // Supprime un article à partir de son id
    public function removeArticle(int $id): void {
        $sql = "DELETE FROM article WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function getCategories(): array {
        // Définition de la requête SQL pour récupérer toutes les catégories
        $sql = "SELECT id, nom FROM categorie ORDER BY id";

        // Préparation et exécution de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute();

        // Retour des catégories (chaque ligne contient 'id' et 'nom')
        return $stmt->fetchAll();
    }

    public function getIdCategorie(int $idArticle): int {
        // Définition de la requête SQL pour récupérer la catégorie d'un article
        $sql = "SELECT id_categorie FROM article WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $idArticle);
        $stmt->execute();

        $ligne = $stmt->fetch();

        // Si l'article n'existe pas, on renvoie 0
        if (!$ligne) {
            return 0;
        }

        return $ligne['id_categorie'];
    }

    // Vérifie les données d'un article.
    // Si une donnée est invalide, on lance une exception avec le nom de la clé de traduction.
    public function validerArticle(string $titre, string $contenu, int $idCategorie): void {
        if (strlen($titre) < 3 || strlen($titre) > 255) {
            throw new Exception('erreur_titre_longueur');
        }

        if (strlen($contenu) < 10) {
            throw new Exception('erreur_contenu_longueur');
        }

        // La catégorie doit exister dans la table categorie
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

    public function addArticle(string $titre, string $contenu, ?string $image, int $idCategorie, int $idAuteur): void {
        // Définition de la requête SQL pour ajouter un article
        $sql = "INSERT INTO article (titre, contenu, image_couverture, date_publication, id_categorie, id_auteur)
                VALUES (:titre, :contenu, :image, NOW(), :id_categorie, :id_auteur)";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':titre', $titre);
        $stmt->bindValue(':contenu', $contenu);
        $stmt->bindValue(':image', $image);
        $stmt->bindValue(':id_categorie', $idCategorie);
        $stmt->bindValue(':id_auteur', $idAuteur);

        // Exécution de la requête SQL
        $stmt->execute();
    }

    public function updateArticle(int $id, string $titre, string $contenu, ?string $image, int $idCategorie): void {
        // Définition de la requête SQL pour modifier un article
        $sql = "UPDATE article
                SET titre = :titre, contenu = :contenu, image_couverture = :image, id_categorie = :id_categorie
                WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':titre', $titre);
        $stmt->bindValue(':contenu', $contenu);
        $stmt->bindValue(':image', $image);
        $stmt->bindValue(':id_categorie', $idCategorie);
        $stmt->bindValue(':id', $id);

        // Exécution de la requête SQL
        $stmt->execute();
    }
}
