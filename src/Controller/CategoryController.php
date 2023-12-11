<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category', name: 'app_category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RequestStack $requestStack, CategoryRepository $categoryRepository): Response
    {
        $session = $requestStack->getSession();
        if (!$session->has('total')) {
            $session->set('total', 0);
        }
        $total = $session->get('total');
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'total' => $total,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', methods: ['GET', 'POST'], name: 'new')]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'La nouvelle série a bien été créée !');

            return $this->redirectToRoute('app_category_index');
        }
        return $this->render('category/new.html.twig', [
            'form' => $form, 
        ]);
    }
    #[Route('/{categoryName}', requirements: ['categoryName'=>'\w+'], methods: ['GET'], name: 'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneByName($categoryName);
        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name ' .$categoryName. ' found in category\'s table.'
            );
        }
        $programsByCategory = $programRepository->findByCategory($category, ['id' => 'DESC'], 50, 0);

        return $this->render('category/show.html.twig', [
            'programsByCategory' => $programsByCategory,
            'category' => $category,
        ]);
    }

    #[Route('/{categoryName}/edit', requirements: ['categoryName'=>'\w+'], name: 'edit', methods: ['GET', 'POST'])]
    public function edit(string $categoryName, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = $categoryRepository->findOneByName($categoryName);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('category/edit.html.twig', [
    //         'episode' => $category,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{categoryName}', name: 'delete', methods: ['POST'])]
    public function delete(string $categoryName, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = $categoryRepository->findOneByName($categoryName);
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/{id}', name: 'delete', methods: ['POST'])]
    // public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($category);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    // }
}
