<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Address;

class MailService
{

    private $mailer;
    private $paramBag;

    //On injecte dans le constructeur le MailerInterface
    public function __construct (MailerInterface $mailer,ParameterBagInterface $paramBag)
    {
        $this -> mailer = $mailer;
        $this -> paramBag = $paramBag;
    }

    public function sendMail ($expediteur, $destinataire, $sujet, $message)
    {
        // // On se sert du parameterBag et du nom du paramètre ('image_directory') pour récupèrer le chemin du dossier "images"
        // $dossiers_images = $this -> paramBag -> get ('images_directory');

        $email = (new TemplatedEmail ())
        
        -> from ($expediteur)

        -> to (new Address($destinataire))

        // -> cc ('cc@example.com')
        // -> bcc ('bcc@example.com')
        // -> replyTo ('fabien@example.com')
        // -> priority (Email::PRIORITY_HIGH)
        
        -> subject ($sujet)

        // le chemin de la vue Twig à utiliser dans le mail
        ->htmlTemplate('emails/signup.html.twig')

        // un tableau de variable à passer à la vue; 
        // on choisit le nom d'une variable pour la vue et on lui attribue une valeur (comme dans la fonction `render`) :
        ->context(
            [
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
            ])
        ;
      
        $this -> mailer -> send($email);

    }
    
}    
    
