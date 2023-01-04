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
    #[Route('/codebar', name: 'app_student_barcode', methods: ['GET'])]
    public function generatebarcodeimg(StudentRepository $studentRepository) 
    {

// create new PDF document
$pdf = new \TCPDF;
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 027');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 027', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set a barcode on the page footer
$pdf->setBarcode(date('Y-m-d H:i:s'));

// set font
$pdf->SetFont('helvetica', '', 11);

// add a page
$pdf->AddPage();

// print a message
$txt = "You can also export 1D barcodes in other formats (PNG, SVG, HTML). Check the examples inside the barcodes directory.\n";
$pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);
$pdf->SetY(30);

// -----------------------------------------------------------------------------

$pdf->SetFont('helvetica', '', 10);

// define barcode style
$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);

// PRINT VARIOUS 1D BARCODES
$students = $studentRepository->findAll();
foreach ($students as $student){
$pdf->AddPage();

// CODE 128 AUTO
$pdf->Cell(0, 0, 'CODE 128 AUTO', 0, 1);
$pdf->write1DBarcode('CODE 128 AUTO', 'C128', '', '', '', 18, 0.4, $style, 'N');

$pdf->Ln();
return $pdf->output('barcode.pdf');
}
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
