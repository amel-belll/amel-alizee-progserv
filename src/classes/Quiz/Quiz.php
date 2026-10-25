<?php

namespace Quiz;

class Quiz {
    // Propriétés privées pour assurer l'encapsulation
    private int $id;
    private string $titre;
    private ?string $description;
    private ?string $image;

    // Constructeur pour initialiser l'objet
    public function __construct(int $id, string $titre, ?string $description, ?string $image) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->image = $image;
    }

    // Getters pour accéder aux propriétés
    public function getId(): int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getImage(): ?string {
        return $this->image;
    }
}
