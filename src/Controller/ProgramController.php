<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }

    #[Route('/{id}', requirements: ['id'=>'\d+'], methods: ['GET'], name: 'show')]
    public function show(int $id): Response 
    {
        return $this->render('program/show.html.twig', [
            'id' => $id,
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