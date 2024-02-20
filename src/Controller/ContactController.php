<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use symfony\bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    
    public function index (Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer, MailService $ms): Response
    {
        $form = $this -> createForm(ContactFormType::class);
        
        $form -> handleRequest($request);

        if ($form -> isSubmitted () && $form -> isValid ())
        {
            //on crée une instance de Contact
            $message = new Contact ();

            // Traitement des données du formulaire
            $message = $form->getData();

            //on stocke les données récupérées dans la variable $message
            $message->setUtilisateur($message->getUtilisateur());
            $message->setEmail($message->getEmail());
            $message->setObject($message->getObject());
            $message->setMessage($message->getMessage());

            $entityManager -> persist ($message);
            $entityManager -> flush ();


            // envoi de mail avec notre service MailService
            $email = $ms -> sendMail ('souarei404@gmail.com', $message -> getEmail (), $message -> getObjet (),$message -> getMessage ());
            dd ($message -> getEmail ());

            return $this -> redirectToRoute ('app_accueil');

        }

        // A partir de la version 6.2 de Symfony, on n'est plus obligé d'écrire $form->createView(), il suffit de passer l'instance de FormInterface à la méthode render
        return $this -> render('contact/index.html.twig',
        [
            'form' => $form->createView(),
        //  'form' => $form
        ]);
    }
}
