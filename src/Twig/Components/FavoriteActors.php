<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Entity\Actor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class FavoriteActors
{
    use DefaultActionTrait;

    #[LiveProp]
    public Actor $actor;

    public function __construct(
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[LiveAction]
    public function toggle(): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if ($user->getFavoriteActors()->contains($this->actor)) {
            $user->removeFavoriteActor($this->actor);
        }else {
            $user->addFavoriteActor($this->actor);
        }
        $this->entityManager->flush();
    }
    
}
