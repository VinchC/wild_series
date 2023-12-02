<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    #[Route('/{program}', requirements: ['program'=>'\d+'], methods: ['GET'], name: 'show')]
    public function show(Program $program): Response 
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' .$program->id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{program}/season/{season}', requirements: ['program'=>'\d+', 'season'=>'\d+'], methods: ['GET'], name: 'season_show')]
    public function showSeason(Program $program, Season $season, EpisodeRepository $episodeRepository): Response 
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with name ' .$program->id. ' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with name ' .$season->id. ' found in season\'s table.'
            );
        }
        
        $episodesBySeason = $episodeRepository->findBySeason($season);
        return $this->render('program/season_show.html.twig', [
            'program' => $program, 'season' => $season, 'episodes' => $episodesBySeason,
        ]);
    }

    #[Route('/{program}/season/{season}/episode/{episode}', requirements: ['program'=>'\d+', 'season'=>'\d+', 'episode'=>'\d+'], methods: ['GET'], name: 'episode_show')]
    public function showEpisode(Program $program, Season $season, Episode $episode): Response 
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with name ' .$program->title. ' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with name ' .$season->number. ' found in season\'s table.'
            );
        }

        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode with name ' .$episode->title. ' found in episode\'s table.'
            );
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program, 'season' => $season, 'episode' => $episode,
        ]);
    }

}