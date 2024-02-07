<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\AbstractList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]

    public function index (): Response
    {
        return $this -> render ('accueil/index.html.twig', 
        [
            'controller_name' => 'AccueilController',
        ]
        );
    }
}
