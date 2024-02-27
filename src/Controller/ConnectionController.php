<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnectionController extends AbstractController
{
    #[Route (path: '/login', name: 'app_login')]

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $erreur = $authenticationUtils -> getLastAuthenticationError ();

        // last username entered by the user
        $lastUsername = $authenticationUtils -> getLastUsername ();

        if ($this -> getUser ())
        {
            return $this -> redirectToRoute ('app_bienvenue');
        }

        return $this -> render ('connection/index.html.twig', 
        [
            'last_username' => $lastUsername,
            'erreur' => $erreur,
        ]
        );
    }

    #[Route (path: '/logout', name: 'app_logout')]

    public function logout(): void
    {
        throw new \LogicException ('This method can be blank - it will be intercepted by the logout key on your firewall.');

    }
    
}
