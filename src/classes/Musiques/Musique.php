<?php

namespace Musiques;

class Musique {
    // Propriétés privées pour assurer l'encapsulation
    private int $id;
    private string $titre;
    private string $artiste;
    private ?string $genre;
    private ?string $pochette;
    private string $lien;

    // Constructeur pour initialiser l'objet
    public function __construct(int $id, string $titre, string $artiste, ?string $genre, ?string $pochette, string $lien) {
        $this->id = $id;
        $this->titre = $titre;
        $this->artiste = $artiste;
        $this->genre = $genre;
        $this->pochette = $pochette;
        $this->lien = $lien;
    }

    // Getters pour accéder aux propriétés
    public function getId(): int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getArtiste(): string {
        return $this->artiste;
    }

    public function getGenre(): ?string {
        return $this->genre;
    }

    public function getPochette(): ?string {
        return $this->pochette;
    }

    public function getLien(): string {
        return $this->lien;
    }
}
