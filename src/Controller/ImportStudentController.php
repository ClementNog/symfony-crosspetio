<?php

namespace App\Controller;

use PDO;
use PDOException;
use League\Csv\Reader;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportStudentController extends AbstractController
{
    #[Route('/import/student', name: 'app_import_student')]
    public function index(): Response
    {
        $serveur = "localhost";
        $dbname = "crosspetio";
        $user = "root";
        $pass = "";
        
        $rows = array();
        $error_message = "";        

        try{
            $connexion = new PDO("mysql:host=$serveur;port=3306;dbname=$dbname",$user,$pass);
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sth = $connexion->prepare("SELECT * from ranking");
            $sth->execute();

        $row = 1;
        if (($handle = fopen("import.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
        fclose($handle);
        
        return $this->render('import_student/index.html.twig', [
            'controller_name' => 'ImportStudentController',
        ]);
    }


        
    }
}