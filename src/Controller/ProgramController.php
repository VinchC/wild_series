<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Form\SearchProgramType;
use App\Service\ProgramDuration;
use Symfony\Component\Mime\Email;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/program', name: 'app_program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, RequestStack $requestStack, ProgramRepository $programRepository): Response
    {
        $session = $requestStack->getSession();
        if (!$session->has('total')) {
            $session->set('total', 0); // if total doesn’t exist in session, it is initialized.
        }   

        $total = $session->get('total');

        $form = $this->createForm(SearchProgramType::class); // Création du formulaire de recherche défini dans le ...Type
        $form->handleRequest($request); // permet de traiter la requête de l'utiliateur à la soumission de celle-ci

        if ($form->isSubmitted() && $form->isValid()) { // vérification de la validation du formulaire à sa soumission
            $name = $form->getData()['searchRRR']; // Récupération du contenu de la requête dans le champs du formulaire
            $programs = $programRepository->findLikeName($name); 
            // $programs = $programRepository->findBy(['title' => $search]); // Récupération des séries dont le titre correspond au contenu de la requête
        } else {
            $programs = $programRepository->findAll(); // si pas de correspondance, affichage de toutes les séries
        }
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
            'total' => $total,
            'form' => $form,
        ]);
    }

    #[Route('/new', methods: ['GET', 'POST'], name: 'new')]
    public function new(
        Request $request, 
        MailerInterface $mailer,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $program = new Program();
        $user = $this->getUser();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($user);
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to($this->getParameter('mailer_from'))
            ->subject('Une nouvelle série vient d\'être publiée !')
            ->html($this->renderView('program/newProgramEmail.html.twig', [
                'program' => $program,
            ]));

            $mailer->send($email);

            $this->addFlash('success', 'La série a bien été créée !');
            
            return $this->redirectToRoute('app_program_index');
        }
        return $this->render('program/new.html.twig', [
            'form' => $form, 
        ]);
    }

    #[Route('/{programSlug}', requirements: ['program'=>'\d+'], methods: ['GET'], name: 'show')]
    public function show(Program $program, ProgramDuration $programDuration): Response 
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' .$program->id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program)
        ]);
    }

    #[Route('/{programSlug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $program->getOwner()) {
            throw $this->createAccessDeniedException('Seule la/le propriétaire peut modifier sa série !');
        }
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('warning', 'La série a bien été modifiée !');

            return $this->redirectToRoute('app_program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{programSlug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
            $this->addFlash('danger', 'La série a bien été supprimée !');
        }

        return $this->redirectToRoute('app_program_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{programSlug}/season/{season}', requirements: ['program'=>'\d+', 'season'=>'\d+'], methods: ['GET'], name: 'season_show')]
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

    #[Route('/{programSlug}/season/{season}/episode/{episodeSlug}', requirements: ['program'=>'\d+', 'season'=>'\d+', 'episode'=>'\d+'], methods: ['GET', 'POST'], name: 'episode_show')]
    public function showEpisode(Program $program, Season $season, Episode $episode, Request $request, EpisodeRepository $episodeRepository, EntityManagerInterface $entityManager): Response 
    {
        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setEpisode($episode);
            $comment->setAuthor($user);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Le commentaire a bien été créé !');

            $episodesBySeason = $episodeRepository->findBySeason($season);
            return $this->render('program/season_show.html.twig', [
                'program' => $program, 'season' => $season, 'episodes' => $episodesBySeason,
            ]);            
        }

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with name ' .$program->title. ' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with number ' .$season->number. ' found in season\'s table.'
            );
        }

        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode with name ' .$episode->title. ' found in episode\'s table.'
            );
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program, 'season' => $season, 'episode' => $episode, 'form' => $form,
        ]);
    }

}