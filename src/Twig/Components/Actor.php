<?php

namespace App\Twig\Components;

use App\Repository\ActorRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('actor')]
final class Actor
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $search = '';

    public function __construct(
        private ActorRepository $repository
    ) {
    }

    public function searchActors(): ?array 
    {
        return mb_strlen($this->search) ? $this->repository->searchActor($this->search) : null;
        // return mb_strlen($this->search) ? $this->repository->findBy([], ['lastname' => 'ASC']) : null;
    }
}
