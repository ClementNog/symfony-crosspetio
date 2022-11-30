<?php

namespace App\Service;
use App\Entity\Grade;
use League\Csv\Reader;
use App\Entity\Student;
use App\Repository\GradeRepository;
use App\Repository\StudentRepository;
use App\Service\ImportStudentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportStudentService
{
    private StudentRepository $studentRepository;
    private GradeRepository $gradeRepository;
    private EntityManagerInterface $em;

    public function __construct( StudentRepository $studentRepository,  GradeRepository $gradeRepository, EntityManagerInterface $em)
    {
        $this->studentRepository = $studentRepository;
        $this->gradeRepository = $gradeRepository;
        $this->em = $em;
    }

    public function importStudent(SymfonyStyle $io): void
    {
        $io->title('Importation des élèves');

        $student = $this->readCsvFile();

        $io->progressStart(count($student));

        foreach ($student as $arrayStudent) {
            $io->progressAdvance();
            $student = $this->createOrUpdateStudent($arrayStudent);
            $this->em->persist($student);
        }
        $this->em->flush();

        $io->progressFinish();

        $io->success('Importation terminée');
    }

    private function readCsvFile(): Reader
    {
        $csv  = Reader::createFromPath('%kernel.root.dir%/../import/liste_eleves.csv', 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdateStudent(array $arrayStudent): Student
    {
        $student = $this->studentRepository->findOneBy(['lastname' => $arrayStudent['Nom']]);
        if (!$student) {
            $student = new Student();
        }
        $student->setLastname($arrayStudent['lastname'])
            ->setFirstname($arrayStudent['firstname'])
            ->setGender($arrayStudent['gender'])
            ->setMas($arrayStudent['mas']);

        $grade = $this->gradeRepository->findOneBy(['shortname' => $arrayStudent['Grade']]);
        if (!$grade) {
            $grade = new Grade();
            $grade->setShortname($arrayStudent['Grade']);
            print($arrayStudent['Grade']);
            $grade->setlevel($arrayStudent['Grade'][0]);
            $this->em->persist($grade);        
            $this->em->flush();
        }
        $student->setGrade($grade);
        return $student;
    }
}