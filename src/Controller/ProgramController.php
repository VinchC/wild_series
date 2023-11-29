<?php

namespace App\Controller;

use App\Repository\SeasonRepository;
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

    #[Route('/show/{id}', requirements: ['id'=>'\d+'], methods: ['GET'], name: 'show')]
    public function show(ProgramRepository $programRepository, int $id): Response 
    {
        $program = $programRepository->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                "No program with id : '.$id.' found in program's table."
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{programId}/seasons/{seasonId}', methods: ['GET'], name: 'season_show')]
    public function showSeason(ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository, int $programId, int $seasonId): Response 
    {
        $program = $programRepository->findOneById($programId);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with name ' .$programId. ' found in program\'s table.'
            );
        }

        $season = $seasonRepository->findOneById($seasonId);
        if (!$season) {
            throw $this->createNotFoundException(
                'No season with name ' .$seasonId. ' found in season\'s table.'
            );
        }
        
        $episodesBySeason = $episodeRepository->findBySeason($season);
        return $this->render('program/season_show.html.twig', [
            'program' => $program, 'season' => $season, 'episodes' => $episodesBySeason,
        ]);
    }

    // #[Route('/list/{page}', requirements: ['page'=>'\d+'], name: 'list')]
    // public function list(int $page = 1): Response
    // {
    //     return $this->render('program/list.html.twig', [
    //         'page' => $page,
    //     ]);
    // }

    // #[Route('/new', methods: ['GET', 'POST'], name: 'new')]



    // #[Route('/{id}', methods: ['DELETE'], name: 'delete')]
}