<?php

namespace Quiz;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;
use Exception;

class QuizManager {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getQuiz(): array {
        // Définition de la requête SQL pour récupérer tous les quiz
        $sql = "SELECT id, titre, description, image
                FROM quiz
                ORDER BY id DESC";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de tous les quiz
        $lignes = $stmt->fetchAll();

        // Transformation de chaque ligne en objet Quiz
        $quizListe = [];

        foreach ($lignes as $ligne) {
            $quizListe[] = new Quiz(
                $ligne['id'],
                $ligne['titre'],
                $ligne['description'],
                $ligne['image']
            );
        }

        // Retour de tous les quiz
        return $quizListe;
    }
    // Récupère un seul quiz à partir de son id (ou null s'il n'existe pas)
    public function getQuizParId(int $id): ?Quiz {
        $sql = "SELECT id, titre, description, image
                FROM quiz
                WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $ligne = $stmt->fetch();

        if ($ligne === false) {
            return null;
        }

        return new Quiz(
            $ligne['id'],
            $ligne['titre'],
            $ligne['description'],
            $ligne['image']
        );
    }
    // Récupère les questions d'un quiz, chacune avec ses réponses
    public function getQuestions(int $idQuiz): array {
        // 1. Les questions du quiz, dans l'ordre
        $sql = "SELECT id, texte
                FROM question
                WHERE id_quiz = :id_quiz
                ORDER BY ordre";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id_quiz', $idQuiz);
        $stmt->execute();

        $lignesQuestions = $stmt->fetchAll();

        // 2. Pour chaque question, on va chercher ses réponses
        $sqlReponses = "SELECT id, texte, id_aesthetic
                        FROM reponse
                        WHERE id_question = :id_question";

        $stmtReponses = $this->database->getPdo()->prepare($sqlReponses);

        $questions = [];

        foreach ($lignesQuestions as $ligneQuestion) {
            $stmtReponses->bindValue(':id_question', $ligneQuestion['id']);
            $stmtReponses->execute();

            $lignesReponses = $stmtReponses->fetchAll();

            // Transformation de chaque ligne en objet Reponse
            $reponses = [];

            foreach ($lignesReponses as $ligneReponse) {
                $reponses[] = new Reponse(
                    $ligneReponse['id'],
                    $ligneReponse['texte'],
                    $ligneReponse['id_aesthetic']
                );
            }

            // Création de la question avec sa liste de réponses
            $questions[] = new Question(
                $ligneQuestion['id'],
                $ligneQuestion['texte'],
                $reponses
            );
        }

        return $questions;
    }
    // Récupère un aesthetic à partir de son id (ou null s'il n'existe pas)
    public function getAesthetic(int $id): ?Aesthetic {
        $sql = "SELECT id, nom, description, image
                FROM aesthetic
                WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $ligne = $stmt->fetch();

        if ($ligne === false) {
            return null;
        }

        return new Aesthetic(
            $ligne['id'],
            $ligne['nom'],
            $ligne['description'],
            $ligne['image']
        );
    }

    // Enregistre le résultat d'un quiz pour un utilisateur
    public function addResultat(int $idUtilisateur, int $idQuiz, int $idAesthetic): void {
        $sql = "INSERT INTO resultat_quiz (date, id_utilisateur, id_quiz, id_aesthetic)
                VALUES (NOW(), :id_utilisateur, :id_quiz, :id_aesthetic)";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $idUtilisateur);
        $stmt->bindValue(':id_quiz', $idQuiz);
        $stmt->bindValue(':id_aesthetic', $idAesthetic);
        $stmt->execute();
    }

    // Récupère tous les résultats de quiz d'un utilisateur, du plus récent au plus ancien
    public function getResultatsUtilisateur(int $idUtilisateur): array {
        $sql = "SELECT r.date, q.id AS id_quiz, q.titre AS titre_quiz,
                       a.nom AS nom_aesthetic, a.image AS image_aesthetic
                FROM resultat_quiz r
                JOIN quiz q ON r.id_quiz = q.id
                JOIN aesthetic a ON r.id_aesthetic = a.id
                WHERE r.id_utilisateur = :id_utilisateur
                ORDER BY r.date DESC";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $idUtilisateur);
        $stmt->execute();

        $lignes = $stmt->fetchAll();

        $resultats = [];

        foreach ($lignes as $ligne) {
            $resultats[] = new ResultatQuiz(
                new \DateTime($ligne['date']),
                $ligne['id_quiz'],
                $ligne['titre_quiz'],
                $ligne['nom_aesthetic'],
                $ligne['image_aesthetic']
            );
        }

        return $resultats;
    }

    // Supprime un quiz et tout ce qui lui est lié
    public function removeQuiz(int $id): void {
        $pdo = $this->database->getPdo();

        // 1. Les réponses des questions de ce quiz
        $stmt = $pdo->prepare("DELETE FROM reponse WHERE id_question IN (SELECT id FROM question WHERE id_quiz = :id)");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // 2. Les questions du quiz
        $stmt = $pdo->prepare("DELETE FROM question WHERE id_quiz = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // 3. Les résultats enregistrés pour ce quiz
        $stmt = $pdo->prepare("DELETE FROM resultat_quiz WHERE id_quiz = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // 4. Les aesthetics du quiz
        $stmt = $pdo->prepare("DELETE FROM aesthetic WHERE id_quiz = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // 5. Enfin, le quiz lui-même
        $stmt = $pdo->prepare("DELETE FROM quiz WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    // Vérifie les données d'un quiz (lance une exception avec une clé de traduction)
    public function validerQuiz(string $titre): void {
        if (strlen($titre) < 3 || strlen($titre) > 255) {
            throw new Exception('erreur_titre_longueur');
        }
    }

    // Ajoute un nouveau quiz
    public function addQuiz(string $titre, ?string $description, ?string $image): void {
        $sql = "INSERT INTO quiz (titre, description, image) VALUES (:titre, :description, :image)";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':titre', $titre);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':image', $image);
        $stmt->execute();
    }

    // Modifie un quiz existant
    public function updateQuiz(int $id, string $titre, ?string $description, ?string $image): void {
        $sql = "UPDATE quiz SET titre = :titre, description = :description, image = :image WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':titre', $titre);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':image', $image);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    // Récupère tous les aesthetics d'un quiz
    public function getAesthetics(int $idQuiz): array {
        $sql = "SELECT id, nom, description, image FROM aesthetic WHERE id_quiz = :id_quiz ORDER BY id";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id_quiz', $idQuiz);
        $stmt->execute();

        $lignes = $stmt->fetchAll();

        $aesthetics = [];

        foreach ($lignes as $ligne) {
            $aesthetics[] = new Aesthetic(
                $ligne['id'],
                $ligne['nom'],
                $ligne['description'],
                $ligne['image']
            );
        }

        return $aesthetics;
    }

    // Vérifie le nom d'un aesthetic
    public function validerAesthetic(string $nom): void {
        if (strlen($nom) < 2 || strlen($nom) > 100) {
            throw new Exception('erreur_nom_aesthetic');
        }
    }

    // Ajoute un aesthetic à un quiz
    public function addAesthetic(int $idQuiz, string $nom, ?string $description, ?string $image): void {
        $sql = "INSERT INTO aesthetic (nom, description, image, id_quiz) VALUES (:nom, :description, :image, :id_quiz)";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':image', $image);
        $stmt->bindValue(':id_quiz', $idQuiz);
        $stmt->execute();
    }

    // Modifie un aesthetic (seulement s'il appartient bien à ce quiz)
    public function updateAesthetic(int $id, int $idQuiz, string $nom, ?string $description, ?string $image): void {
        $sql = "UPDATE aesthetic SET nom = :nom, description = :description, image = :image
                WHERE id = :id AND id_quiz = :id_quiz";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':image', $image);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':id_quiz', $idQuiz);
        $stmt->execute();
    }

    // Supprime un aesthetic et ce qui en dépend (réponses et résultats)
    public function removeAesthetic(int $id): void {
        $pdo = $this->database->getPdo();

        $stmt = $pdo->prepare("DELETE FROM reponse WHERE id_aesthetic = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM resultat_quiz WHERE id_aesthetic = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM aesthetic WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
