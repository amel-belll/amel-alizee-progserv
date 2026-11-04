<?php

namespace Utilisateurs;

use Database;
use Exception;

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
    // Vérifie les données de l'inscription.

    // Si une donnée est invalide, on lance une exception avec le nom de la clé de traduction.

    public function validerInscription(string $pseudo, string $email, string $motDePasse): void {

        if (strlen($pseudo) < 3 || strlen($pseudo) > 30) {

            throw new Exception('erreur_pseudo_longueur');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            throw new Exception('erreur_email_invalide');
        }

        if (strlen($motDePasse) < 8) {

            throw new Exception('erreur_mot_de_passe_longueur');
        }

        if ($this->pseudoExiste($pseudo)) {

            throw new Exception('erreur_pseudo_utilise');
        }

        if ($this->emailExiste($email)) {

            throw new Exception('erreur_email_utilise');
        }
    }
    // Vérifie les données de la connexion
    public function validerConnexion(string $email, string $motDePasse): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('erreur_email_invalide');
        }

        if (empty($motDePasse)) {
            throw new Exception('erreur_mot_de_passe_obligatoire');
        }
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
    // Récupère un utilisateur à partir de son e-mail (ou null s'il n'existe pas)
    public function getUtilisateurParEmail(string $email): ?Utilisateur {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $ligne = $stmt->fetch();

        if ($ligne === false) {
            return null;
        }

        return new Utilisateur(
            $ligne['id'],
            $ligne['pseudo'],
            $ligne['email'],
            $ligne['mot_de_passe'],
            $ligne['role'],
            (bool) $ligne['est_valide']
        );
    }
}
