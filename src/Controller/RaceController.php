<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Race;
use App\Entity\Student;
use App\Form\RaceType;
use App\Repository\RaceRepository;
use App\Repository\GradeRepository;
use App\Repository\StudentRepository;
use DateTime;
use Doctrine\Common\Collections\Expr\Value;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

#[Route('/race')]
class RaceController extends AbstractController
{
    #[Route('/', name: 'app_race_index', methods: ['GET'])]
    public function index(RaceRepository $raceRepository): Response
    {
        $message = "";

        return $this->render('race/index.html.twig', [
            'races' => $raceRepository->findAll(),
            'message' => $message,
        ]);
    }

    #[Route('/new', name: 'app_race_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RaceRepository $raceRepository, StudentRepository $studentRepository, GradeRepository $gradeRepository,  SluggerInterface $slugger): Response
    {
        $message = "START";

        $race = new Race();
        $form = $this->createForm(RaceType::class, $race);
        $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        if ($form->isSubmitted()) {

            // Import CSV file
            $filenameFile = $form->get('filename')->getData();
            $message .= "filenameFile = '" . $filenameFile . "'";

            // this condition is needed because the 'filename' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($filenameFile) {
                $originalFilename = pathinfo($filenameFile->getClientOriginalName(), PATHINFO_FILENAME);
                $message .= "originalFilename = '" . $originalFilename . "'";

                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $message .= "safeFilename = '" . $safeFilename . "'";

                // $newFilename = $safeFilename . '-' . uniqid() . '.' . $filenameFile->guessExtension();
                $newFilename = $safeFilename . '-' . uniqid() . '.csv';
                $message .= "newFilename = '" . $newFilename . "'";

                // Move the file to the directory where filenames are stored
                try {
                    $message .= "BEFORE move safeFilename = '" . $safeFilename . "'";
                    $filenameFile->move(
                        $this->getParameter('filenames_directory'),
                        $newFilename 
                    );
                    $message .= "AFTER move safeFilename = '" . $safeFilename . "'";
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'filenameFilename' property to store the PDF file name
                // instead of its contents
                $race->setFilename($newFilename);
                $message .= "path + safeFilename = '" . $this->getParameter('filenames_directory') . '/' . $race->getFilename() . "'";

                // $this->getParameter('filenames_directory')
                $serializer = new CsvEncoder();
                // decoding CSV contents
                $data = $serializer->decode(file_get_contents($this->getParameter('filenames_directory') . '/' . $race->getFilename()), 'csv');
                // $message .= "data = '" . print_r($data, true) . "'";

                foreach ($data as $key => $value) {
                    // $message .= "key = '" . print_r($key, true) . "'";
                    $student = new Student();
                    $student->setLastname($value['NUM']);
                    $student->setShortname($value['Prénom']);
                    $student->setLastname($value['Nom']);
                    $student->setGender($value['SEXE']);
                    $student->setMas(floatval($value['VMA']));
                    $student->setObjective(new DateTime()); //$value['TEMPS']

                    //$gradeShortname = substr($value['CLASSE'], 2);
                    $gradeShortname = $value['CLASSE'];
                    $gradeLevel = $value['CLASSE'][0];
                    $grade = $gradeRepository->findOneBy(array('shortname' => $gradeShortname));
                    if (!isset($grade)) {
                        $grade = new Grade();
                        $grade->setShortname($gradeShortname);
                        $grade->setLevel($gradeLevel);
                        $gradeRepository->save($grade, true);
                    }
        
                    $student->setGrade($grade);

                    $studentRepository->save($student, true);
                }

                //$request = "INSERT INTO student(id, shortname, lastname, grade_id, gender, mas, objective) VALUES('NUM', 'Nom', 'Prénom', 'CLASSE', 'SEXE', '', 'TEMPS')";

            }

            $raceRepository->save($race, true);

            return $this->render('race/index.html.twig', [
                'races' => $raceRepository->findAll(),
                'message' => $message,
            ]);
        }

        return $this->renderForm('race/new.html.twig', [
            'race' => $race,
            'form' => $form,
            'message' => $message,
        ]);
    }

    #[Route('/{id}', name: 'app_race_show', methods: ['GET'])]
    public function show(Race $race): Response
    {
        return $this->render('race/show.html.twig', [
            'race' => $race,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_race_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Race $race, RaceRepository $raceRepository): Response
    {
        $form = $this->createForm(RaceType::class, $race);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $raceRepository->save($race, true);

            return $this->redirectToRoute('app_race_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('race/edit.html.twig', [
            'race' => $race,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_race_delete', methods: ['POST'])]
    public function delete(Request $request, Race $race, RaceRepository $raceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $race->getId(), $request->request->get('_token'))) {
            $raceRepository->remove($race, true);
        }

        return $this->redirectToRoute('app_race_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/start', name: 'app_race_start', methods: ['POST'])]
    public function start(Request $request, Race $race, RaceRepository $raceRepository): Response
    {
        $message = "";

        return $this->render('race/index.html.twig', [
            'races' => $raceRepository->findAll(),
            'message' => $message,
        ]);
    }
    
}
