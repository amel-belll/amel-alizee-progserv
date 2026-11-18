<?php

namespace Quiz;

class ResultatQuiz {
    private \DateTime $date;
    private int $idQuiz;
    private string $titreQuiz;
    private string $nomAesthetic;
    private ?string $imageAesthetic;

    public function __construct(\DateTime $date, int $idQuiz, string $titreQuiz, string $nomAesthetic, ?string $imageAesthetic) {
        $this->date = $date;
        $this->idQuiz = $idQuiz;
        $this->titreQuiz = $titreQuiz;
        $this->nomAesthetic = $nomAesthetic;
        $this->imageAesthetic = $imageAesthetic;
    }

    public function getDate(): \DateTime {
        return $this->date;
    }

    public function getIdQuiz(): int {
        return $this->idQuiz;
    }

    public function getTitreQuiz(): string {
        return $this->titreQuiz;
    }

    public function getNomAesthetic(): string {
        return $this->nomAesthetic;
    }

    public function getImageAesthetic(): ?string {
        return $this->imageAesthetic;
    }
}
