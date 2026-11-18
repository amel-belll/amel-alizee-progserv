<?php

namespace Quiz;

class Question {
    private int $id;
    private string $texte;
    private array $reponses;

    public function __construct(int $id, string $texte, array $reponses) {
        $this->id = $id;
        $this->texte = $texte;
        $this->reponses = $reponses;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTexte(): string {
        return $this->texte;
    }

    // Renvoie la liste des objets Reponse de cette question
    public function getReponses(): array {
        return $this->reponses;
    }
}
