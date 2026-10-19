<?php

namespace Articles;

class Article {
    // Propriétés privées pour assurer l'encapsulation
    private int $id;
    private string $titre;
    private string $contenu;
    private ?string $imageCouverture;
    private \DateTime $datePublication;
    private string $categorie;

    // Constructeur pour initialiser l'objet
    public function __construct(int $id, string $titre, string $contenu, ?string $imageCouverture, \DateTime $datePublication, string $categorie) {
        $this->id = $id;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->imageCouverture = $imageCouverture;
        $this->datePublication = $datePublication;
        $this->categorie = $categorie;
    }

    // Getters pour accéder aux propriétés
    public function getId(): int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getContenu(): string {
        return $this->contenu;
    }

    public function getImageCouverture(): ?string {
        return $this->imageCouverture;
    }

    public function getDatePublication(): \DateTime {
        return $this->datePublication;
    }

    public function getCategorie(): string {
        return $this->categorie;
    }
}
