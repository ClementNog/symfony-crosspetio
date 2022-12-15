<?php

namespace App\Controller;

use App\Entity\Run;
use App\Form\RunType;
use App\Entity\Student;
use App\Form\RankingType;
use App\Repository\RunRepository;
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

    #[Route('/{id}/ranking', name: 'app_run_ranking', methods: ['GET', 'POST'])]
    public function ranking(Request $request, Run $run, RunRepository $runRepository): Response
    {
        $form = $this->createForm(RankingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            


            
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
