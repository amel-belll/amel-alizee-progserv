<?php

namespace Quiz;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;

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
}
