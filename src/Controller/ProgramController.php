<?php

namespace App\Controller;

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