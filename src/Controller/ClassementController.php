<?php

namespace App\Controller;

use PDO;
use PDOException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassementController extends AbstractController
{
    
    #[Route('/classement', name: 'app_classement')]
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

            /* Récupération de toutes les lignes d'un jeu de résultats */
            //print("Récupération de toutes les lignes d'un jeu de résultats :\n");
            $rows = $sth->fetchAll();
            //dump($rows);

        }
        catch(PDOException $e){
            $error_message = $e->getMessage();
        }


        return $this->render('classement/index.html.twig', [
            'rows' => $rows,
            'error_message' => $error_message,
            'controller_name' => "Toto",
        ]);
        function ImportStudentCommand(){
            
        }
    }
}
