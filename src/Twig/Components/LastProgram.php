<?php

namespace App\Twig\Components;

use App\Repository\ProgramRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class LastProgram
{
    public int $numPrograms = 6;
    public function __construct(
        private ProgramRepository $programRepository
    ) {
    }

    public function getLastPrograms(): array
    {
        return $this->programRepository->findBy([], ['id' => 'DESC'], $this->numPrograms);
    }
}
