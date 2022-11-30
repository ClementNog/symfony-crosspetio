<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateController extends AbstractController
{
    #[Route('/create', name: 'app_create')]
    public function index(): Response
    {
        return $this->render('create/index.html.twig', [
            'controller_name' => 'CreateController',
        ]);
    }
}
