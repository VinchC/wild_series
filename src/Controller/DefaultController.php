<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findThreeLastPrograms();
        // $programs = $programRepository->findRecentPrograms();
        return $this->render('index.html.twig', [
            'programs' => $programs,
            'welcome' => 'Hello'
        ]);
    }

    #[Route('/todolist', name: 'app_to_do_list')]
    public function displayToDoList(): Response
    {
        return $this->render('todolist.html.twig');
    }
}