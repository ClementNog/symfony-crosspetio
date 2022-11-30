<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{
    #[Route('/ranking', name: 'app_ranking')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $rankings = $entityManager->getRepository(Ranking::class)->findAll();

        return $this->render('ranking/index.html.twig', [
            'controller_name' => 'RankingController',
        ]);
    }
}
