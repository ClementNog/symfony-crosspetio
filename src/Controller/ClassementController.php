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

        function ImportStudentCommand()
        {
        }
    }
}
