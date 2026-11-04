<?php

namespace Utilisateurs;

use Database;

class UtilisateursManager {
    private Database $database;

    public function __construct() {
        $this->database = new Database();
    }

    // Vérifie si un pseudo est déjà utilisé
    public function pseudoExiste(string $pseudo): bool {
        $sql = "SELECT id FROM utilisateur WHERE pseudo = :pseudo";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->execute();

        $ligne = $stmt->fetch();

        return $ligne !== false;
    }

    // Vérifie si un e-mail est déjà utilisé
    public function emailExiste(string $email): bool {
        $sql = "SELECT id FROM utilisateur WHERE email = :email";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $ligne = $stmt->fetch();

        return $ligne !== false;
    }

    // Ajoute un nouvel utilisateur (le mot de passe doit déjà être haché)
    public function addUtilisateur(string $pseudo, string $email, string $motDePasseHache): void {
        $sql = "INSERT INTO utilisateur (pseudo, email, mot_de_passe, date_creation) VALUES (:pseudo, :email, :mot_de_passe, NOW())";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':mot_de_passe', $motDePasseHache);
        $stmt->execute();
    }
}
