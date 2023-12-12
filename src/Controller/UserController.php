<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/my-profile', name: 'app_user_account')]
    public function myAccount(): Response
    {
        $user = $this->getUser();
        return $this->render('user/account.html.twig', [
            'user' => $user,
        ]);
    }
}
