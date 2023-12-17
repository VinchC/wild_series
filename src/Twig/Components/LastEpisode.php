<?php

namespace App\Twig\Components;

use App\Repository\EpisodeRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class LastEpisode
{
    public function __construct(
        private EpisodeRepository $episodeRepository
    ) {
    }

    public function getLastThreeEpisodes(): array
    {
        return $this->episodeRepository->findBy([], ['id' => 'DESC'], 3);
    }
}
