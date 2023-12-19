<?php

namespace App\Twig\Components;

use App\Repository\ProgramRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class ProgramSearch
{
    use DefaultActionTrait;
    
    #[LiveProp(writable: true)]
    public string $query = ''; 

    public function __construct(private ProgramRepository $programRepository) {}

    public function getPrograms(): ?array
    {
        return mb_strlen($this->query) ?$this->programRepository->findLikeName($this->query) : null;
    }
}