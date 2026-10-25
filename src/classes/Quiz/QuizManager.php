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
}
