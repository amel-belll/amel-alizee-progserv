<?php

namespace Images;

class Image {
    private int $id;
    private string $titre;
    private string $fichier;
    private string $categorie;

    public function __construct(int $id, string $titre, string $fichier, string $categorie) {
        $this->id = $id;
        $this->titre = $titre;
        $this->fichier = $fichier;
        $this->categorie = $categorie;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getFichier(): string {
        return $this->fichier;
    }

    public function getCategorie(): string {
        return $this->categorie;
    }
}
