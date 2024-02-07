<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\AbstractList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]

    public function index (): Response
    {
        $info = ['Nom' => 'Souare', 'Prenom' => 'Ibrahima', 'Email' => 'souarei@gmail.com', 'Date' => '15/06/1994'];

        return $this -> render('profil/index.html.twig',
        [
            'informations' => $info
        ]
        );
        
    }
}
