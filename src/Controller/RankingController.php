<?php

namespace App\Controller;

use PDO;
use PDOException;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\RunRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/ranking', name: 'app_ranking')]
class RankingController extends AbstractController
{
    
    #[Route('/', name: 'app_ranking_index')]
    public function index(): Response
    {

        $error_message = "";        
        return $this->render('ranking/index.html.twig', [
            'error_message' => $error_message,
            'controller_name' => "ranking",
        ]);
    }

    #[Route('/rank', name: 'app_ranking_rank', methods: ['GET', 'POST'])]
    public function rank(Request $request, StudentRepository $studentRepository, RunRepository $run): Response
    {
        $error_message = "";
        // $form = $this->createForm(Ranking::class);
    
        $students = $studentRepository->findAll();
    
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
            // for($i = 0; $i<$student; $i++){
            //     if()
            //     $rankings = array (
            //         $student->getId() => $student->getendRace()
            //     );
            // }
            // arsort($rankings);
        // }

        return $this->render('ranking/index.html.twig', [
            // 'rankings' => $rankings,
            'students' => $students,
            'run' => $run,
            'error_message' => $error_message,
        ]);
    }
}
