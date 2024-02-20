<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/email')]

    // Fonction sendEmail dans le controller MailerIn.. qui attend une reponse
    public function sendEmail (MailerInterface $mailer): Response
    {
        // création du email
        $email = (new TemplatedEmail ())
        
            -> from ('souarei404@gmail.com')

            -> to (new Address('lsf.tdg@gmail.com'))

            // -> cc ('cc@example.com')
            // -> bcc ('bcc@example.com')
            // -> replyTo ('fabien@example.com')
            // -> priority (Email::PRIORITY_HIGH)
            
            -> subject ('Time for symfony Mailer !')

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
          
        $mailer -> send($email);

        // Retourner une réponse
        return $this -> redirecttoRoute ('app_accueil');
    }



    
    /*******************************************************************************************************************************
     *   EMAIL AVEC PLUS DE FONCTIONALITÉ
    *******************************************************************************************************************************/




    // public function sendEmail (MailerInterface $mailer): Response
    // {
    //     $email = (new TemplatedEmail ())
        
    //         -> from ('souarei404@gmail.com')

    //         -> to (new Address ('nesrine@gmail.com'))

    //         -> subject ('Time for symfony Mailer !')

    //             // le chemin de la vue Twig à utiliser dans le mail
    //         -> htmlTemplate ('emails/signup.html.twig')

    //             // un tableau de variable à passer à la vue; 
    //             //  on choisit le nom d'une variable pour la vue et on lui attribue une valeur (comme dans la fonction `render`) :
    //         -> context (
    //             [

    //                 'expiration_date' => new \DateTime ('+7 days'),
    //                 'username' => 'foo',
    //             ])
    //         ;

    //     $mailer -> send($email);

    //     // Retourner une réponse
    //     return $this -> redirecttoRoute ('app_accueil');
    // }


    

    /*****************************************************************************************************************************
     * JOINDRE DES FICHIERS 3 POSIBILITÉ 
    *****************************************************************************************************************************/
    



    // public function sendEmail (MailerInterface $mailer): Response
    // {
    //     $email = (new Email ())
        
    //         -> from ('souarei404@gmail.com')

    //         -> to (new Address ('nesrine@gmail.com'))

    //         -> cc ('cc@example.com')
    //         -> bcc ('bcc@example.com')
    //         -> replyTo ('fabien@example.com')
    //         -> priority (Email::PRIORITY_HIGH)
            
    //         -> subject ('Time for symfony Mailer !')

    //         (1) -> addPart (new DataPart (new File ('/path/to/documents/terms-of-use.pdf')))

    //             // vous pouvez, si vous le souhaitez, demander aux clients mail d'afficher un certain nom pour le fichier 
    //         (2) -> addPart (new DataPart (new File ('/path/to/documents/privacy.pdf'), 'Privacy Policy'))

    //             // vous pouvez aussi spécifier le type de document (autrement, il est deviné)
    //         (3) -> addPart (new DataPart (new File ('/path/to/documents/contact.doc'), 'Contact', 'application/msword'))


    //             // le chemin de la vue Twig à utiliser dans le mail
    //         -> htmlTemplate ('emails/signup.html.twig')

    //         -> text ('Sending emails is fun again !')
    //         -> html ('<p>See Twig integration for better HTML integration !</p>');
            
    //         -> context (
    //             [

    //                 'expiration_date' => new \DateTime ('+7 days'),
    //                 'username' => 'foo',
    //             ])
    //         ;

    //     $mailer -> send($email);

    //     Retourner une réponse
    //     return $this -> redirecttoRoute ('app_accueil');
    // }



    
    /*******************************************************************************************************************************
    * INTÉGRER DES IMAGES 
    *******************************************************************************************************************************/
     



    // public function sendEmail (MailerInterface $mailer): Response
    // {
    //     $email = (new Email ())
        
    //         -> from ('souarei404@gmail.com')

    //         -> to (new Address ('nesrine@gmail.com'))

    //         // -> cc ('cc@example.com')
    //         // -> bcc ('bcc@example.com')
    //         // -> replyTo ('fabien@example.com')
    //         // -> priority (Email::PRIORITY_HIGH)
            
    //         -> subject ('Time for symfony Mailer !')

    //         -> addPart((new Data (fopen ('/path/to/images/logo.pgn', 'r'), 'logo', 'images/pgn')) -> asInline ())
    //         -> addPart((new Data (new File ('/path/to/images/signature.gif'), 'footer-signature', 'images/gif')) -> asInline ())

    //         // utiliser la syntaxe 'cid:' + "nom de l'image intégrée " pour référencer l'image
    //         -> html ('<img src="cid:logo"> ... <img src="cid:footer-signature"> ...')

    //         // utiliser la même syntaxe pour les images intégrées en tant que background
    //         -> html ('... <div background="cid:footer-signature"> ... </div> ...')

           
    //         // le chemin de la vue Twig à utiliser dans le mail
    //         -> htmlTemplate ('emails/signup.html.twig')

    //             // -> text ('Sending emails is fun again !')
    //             // -> html ('<p>See Twig integration for better HTML integration !</p>');
            
    //         -> context (
    //             [

    //                 'expiration_date' => new \DateTime ('+7 days'),
    //                 'username' => 'foo',
    //             ])
    //         ;

    //     $mailer -> send($email);

    //     // Retourner une réponse
    //     return $this -> redirecttoRoute ('app_accueil');
    // }
    
}

