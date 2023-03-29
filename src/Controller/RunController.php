<?php

namespace App\Controller;

use App\Entity\Run;
use App\Form\RunType;
use App\Entity\Student;
use App\Form\RankingType;
use App\Repository\RunRepository;
use App\Repository\StudentRepository;
use Date;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/run')]
class RunController extends AbstractController
{   
    #[Route('/', name: 'app_run_index', methods: ['GET'])]
    public function index(RunRepository $runRepository): Response
    {
        return $this->render('run/index.html.twig', [
            'runs' => $runRepository->findAll(),
        ]);
    }

    #[Route('/start', name: 'app_run_start', methods: ['GET'])]
    public function start(Request $request, RunRepository $runRepository): Response
    {
        date_default_timezone_set("Europe/Paris");
        $start_string = date("Y:m:d H:i:s");
        $run = new Run();
        $run->setStart(new DateTime());
        $runRepository->save($run, true);
            return $this->redirectToRoute('app_run_index', [], Response::HTTP_SEE_OTHER);
        return $this->render('run/start.html.twig', [
            'time' => $start_string,
            
        ]);
    }

    #[Route('/{id}/ranking', name: 'app_run_ranking', methods: ['GET', 'POST'])]
    public function ranking(Request $request, Run $run,StudentRepository $studentRepository, RunRepository $runRepository): Response
    {
        $form = $this->createForm(RankingType::class);
        $form->handleRequest($request);
        $list = array();
        if ($form->isSubmitted() && $form->isValid()) {
            $grade = $form->get('grade')->getData();
            $gender = $form->get('gender');
            $level = $form->get('level')->getData();
            if ($grade->getShortname() == '0 Null' && $level != null){
                
                // il faut rajouter les if et les conditions pour séparer les filles des garçons
                foreach($studentRepository->findAll() as $students){
                    $studlevel = $students->getGrade()->getLevel();
                    if( $studlevel == $level){
                        
                        array_push($list, $students);
                        
                    }
                }
                dump($list);
                return $this->render('run/ranking-result.html.twig', [
                    'list' => $list,
                    'level' => $level,
                ]);
                

            }
            else if ($grade->getShortname() == '0 Null' && $level == null){
                
                
                foreach($studentRepository->findAll() as $students){                        
                    array_push($list, $students);
                }
                return $this->render('run/ranking-result.html.twig', [
                    'list' => $list,
                    'level' => null,
                ]);
            }

            foreach($studentRepository->findAll() as $students){
                $studgrade = $students->getGrade();
                $studgrade = $studgrade->getId();
                
                if($studgrade == $grade->getId())
                    array_push($list, $students);
                    
            }
            dump($list);
            $grade = $grade->getId();
            return $this->render('run/ranking-result.html.twig', [
                'list' => $list,
                'level' => $grade

            ]);
                  
            dump($grade);
            dump($gender);

            return $this->renderForm('run/ranking-result.html.twig', [
                'form' => $form,

            ]);
        }
    
        return $this->renderForm('run/ranking-form.html.twig', [
            'form' => $form,
            
        ]);
    }

    #[Route('/new', name: 'app_run_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RunRepository $runRepository): Response
    {
        $run = new Run();
        $form = $this->createForm(RunType::class, $run);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $runRepository->save($run, true);

            return $this->redirectToRoute('app_run_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('run/new.html.twig', [
            'run' => $run,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_run_show', methods: ['GET'])]
    public function show(Run $run): Response
    {
        return $this->render('run/show.html.twig', [
            'run' => $run,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_run_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Run $run, RunRepository $runRepository): Response
    {
        $form = $this->createForm(RunType::class, $run);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $runRepository->save($run, true);

            return $this->redirectToRoute('app_run_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('run/edit.html.twig', [
            'run' => $run,
            'form' => $form,
        ]);
    }  

    #[Route('/{id}', name: 'app_run_delete', methods: ['POST'])]
    public function delete(Request $request, Run $run, RunRepository $runRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$run->getId(), $request->request->get('_token'))) {
            $runRepository->remove($run, true);
        }

        return $this->redirectToRoute('app_run_index', [], Response::HTTP_SEE_OTHER);
    }
   
}
