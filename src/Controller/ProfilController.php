<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*****************************************************************************************************
 *                                      GESTIONS ACTION LIÉES AU PROFIL
*****************************************************************************************************/

class ProfilController extends AbstractController
{
    // accessible que depuis l'intérieur de la classe où elle est déclarée. 
    private $userRepo;

    // Il accepte un argument de type UtilisateurRepository appeler $userRepo
    
    /*******************************************************************    
    * ASSIGNATION DE PROPRIÉTÉ
    *******************************************************************/
    
    public function __construct (UtilisateurRepository $userRepo)
    {
        $this -> userRepo = $userRepo;
    }

    #[Route('/profil', name: 'app_profil')]

    /*******************************************************************    
    * RESPONSABLE DE GESTIONS DE LA PAGE PROFIL
    *******************************************************************/

    public function index (): Response
    {

        // getUser() renvoie l'utilisateur actuellement authentifié
        // getUserIdentifier() renvoie l'identifiant unique de cet utilisateur
        $identifiant = $this-> getUser () -> getUserIdentifier ();

        // si un utilisateur est connecté. Si oui, elle continue à exécuter le code à l'intérieur du bloc
        if ($identifiant)
        {
            // si l'email trouvé dans la base correspondant à l'identifiant unique de l'utilisateur connecté il sera stocker dans info
            $info = $this -> userRepo -> findOneBy (["email" => $identifiant]);

            // affiche la page
            return $this -> render('information/index.html.twig',
            [
                // affiche le template avec les infos trouvé 
                'info' => $info
            ]);
        }
        
    }

}
