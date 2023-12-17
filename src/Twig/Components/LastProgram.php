<?php

namespace App\Twig\Components;

use App\Repository\ProgramRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class LastProgram
{
    public function __construct(
        private ProgramRepository $programRepository
    ) {
    }

    public function getLastThreePrograms(): array
    {
        return $this->programRepository->findBy([], ['id' => 'DESC'], 3);
    }
}
