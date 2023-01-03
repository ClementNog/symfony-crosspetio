<?php

namespace App\Controller;

use App\Entity\Run;
use App\Entity\Student;
use App\Form\StudentType;

use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/barcode', name: 'app_student_barcode', methods: ['GET'])]
    public function generatebarcode(StudentRepository $studentRepository): Response
    {
        $barcode = "";
        $year = substr(date('Y'), 2);
        $students = $studentRepository->findAll();
        foreach ($students as $student) {
            $id = $student->getId();
            $gender = $student->getGender();
            $shortname = $student->getshortname();
            $lastname = $student->getlastname();
            if ($id < 10) {
                $barcode = $gender . "-" . $year . "-" . $shortname[0] . $lastname[0] . "-00" . $id;
            } else if ($id < 100) {
                $barcode = $gender . "-" . $year . "-" . $shortname[0] . $lastname[0] . "-0" . $id;
            } else {
                $barcode = $gender . "-" . $year . "-" . $shortname[0] . $lastname[0] . "-" . $id;
            }
            $student->setBarcode($barcode);
            $studentRepository->save($student, true);
        }
        return $this->renderForm('student/barcode.html.twig', [
            'barcode' => $barcode,
            'students' => $students,

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
        if ($this->isCsrfTokenValid('delete' . $student->getId(), $request->request->get('_token'))) {
            $studentRepository->remove($student, true);
        }

        return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/compute', name: 'app_student_compute', methods: ['POST'])]
    public function compute(Run $run, Student $student, StudentRepository $studentRepository): Response
    {



        //$start::sub(DateInterval $interval): DateTime

        $id = $_GET['id'];

        foreach ($studentRepository as $key => $stud) {
            if (($stud == $id) && ($key == "endrace")) {
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
