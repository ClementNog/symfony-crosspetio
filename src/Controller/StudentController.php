<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Run;
use App\Form\StudentType;

use App\Repository\StudentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: 'app_student_index', methods: ['GET'])]
    public function index(StudentRepository $studentRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StudentRepository $studentRepository): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentRepository->save($student, true);

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/new.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_show', methods: ['GET'])]
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_student_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentRepository->save($student, true);

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_delete', methods: ['POST'])]
    public function delete(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $studentRepository->remove($student, true);
        }

        return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/form', name: 'app_student_form', methods: ['POST'])]
    public function form(Run $run, Student $student, StudentRepository $studentRepository): Response
    {$form  = $this->createFormBuilder()
            ->add('Run', EntityType::class, [
                'class'  => Run::class
            ])
            ->getForm()
            ;
            return $this->renderForm('app_student_index', compact('form'));
    }

    #[Route('/compute', name: 'app_student_compute', methods: ['POST'])]
    public function compute(Run $run, Student $student, StudentRepository $studentRepository): Response
    {

        

        //$start::sub(DateInterval $interval): DateTime
        
        $id = $_GET['id'];
        
        foreach($studentRepository as $key => $stud){
            if (($stud == $id) && ($key == "endrace")){
                $runningTime = $stud->diff($run->getStart());
        }
        $runningTime = "bonjour";
        return $this->renderForm('student/compute.html.twig', [
            'runningTime' => $runningTime,
            'students' => $studentRepository->findAll(),
            
        ]);
    }

    }

    
}
