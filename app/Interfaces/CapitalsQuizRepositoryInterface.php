<?php

namespace App\Interfaces;

interface CapitalsQuizRepositoryInterface
{
    public function getRandomCountry(): array;
    public function getCapitalAnswer(string $answer): array;
    public function exitQuiz(): bool;
}
