<?php

namespace Utilisateurs;

class Utilisateur {
    private int $id;
    private string $pseudo;
    private string $email;
    private string $motDePasse;
    private string $role;
    private bool $estValide;

    public function __construct(int $id, string $pseudo, string $email, string $motDePasse, string $role, bool $estValide) {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
        $this->role = $role;
        $this->estValide = $estValide;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getPseudo(): string {
        return $this->pseudo;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getMotDePasse(): string {
        return $this->motDePasse;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getEstValide(): bool {
        return $this->estValide;
    }
}
