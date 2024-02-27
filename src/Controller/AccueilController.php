<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\DiscRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{

    private $artistRepo;
    private $discRepo;

    public function __construct (ArtistRepository $artistRepo, DiscRepository $discRepo)
    {
        
        $this -> artistRepo = $artistRepo;
        $this -> discRepo = $discRepo;

    }

    #[Route ('/accueil', name: 'app_accueil')]

    public function index (): Response
    {

        //on appelle la fonction `findAll()` du repository de la classe `Artist` afin de récupérer tous les artists de la base de données;
        $artistes = $this -> artistRepo -> findAll ();

        return $this -> render ('accueil/index.html.twig', 
        [
            'controller_name' => 'AccueilController',

            //on va envoyer à la vue notre variable qui stocke un tableau d'objets $artistes (c'est-à-dire tous les artistes trouvés dans la base de données)
            'artistes' => $artistes
        ]
        );

    }

    #[Route('/bienvenue', name: 'app_bienvenue')]

    public function bienvenue (): Response
    {

        return $this -> render ('bienvenue/bienvenue.html.twig', 
        [
            'controller_name' => 'AccueilController'
        ]
        );

    }

}
