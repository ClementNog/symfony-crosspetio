<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BarcodeCodeController extends AbstractController
{
    #[Route('/barcode/code', name: 'app_barcode_code')]
    public function index(): Response
    {
        return $this->render('barcode_code/index.html.twig', [
            'controller_name' => 'BarcodeCodeController',
        ]);
    }
}