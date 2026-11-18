<?php

namespace Quiz;

class Reponse {
    private int $id;
    private string $texte;
    private int $idAesthetic;

    public function __construct(int $id, string $texte, int $idAesthetic) {
        $this->id = $id;
        $this->texte = $texte;
        $this->idAesthetic = $idAesthetic;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTexte(): string {
        return $this->texte;
    }

    public function getIdAesthetic(): int {
        return $this->idAesthetic;
    }
}
