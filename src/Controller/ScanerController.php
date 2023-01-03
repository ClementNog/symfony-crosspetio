<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScanerController extends AbstractController
{
    #[Route('/scaner', name: 'app_scaner')]
    public function index(): Response
    {
        return $this->render('scaner/index.html.twig', [
            'controller_name' => 'ScanerController',
        ]);
    }
}
