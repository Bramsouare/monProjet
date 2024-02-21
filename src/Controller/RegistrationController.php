<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/*****************************************************************************************************
 *                                      ACTION ENREGISTREMENT FORMULAIRE
*****************************************************************************************************/

class RegistrationController extends AbstractController
{
    // dépendance nécessaire pour envoyer des e-mails de vérification lors de l'inscription.
    private EmailVerifier $emailVerifier;

    /************************************************************************
     * ASSIGNATION DE PROPRIÉTÉ
    ************************************************************************/

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    // une fois sur la page la method sera executer
    #[Route('/register', name: 'app_register')]

    /************************************************************************************************************************
     * REQUEST: TOUTES LES INFOS    /    USERPASSWORDHACHER: HACHE MDP    /    ENTITYMANAGER: INTERAGIE AVEC BASE DE DONNÉES  
    ************************************************************************************************************************/

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // création utilisateur
        $user = new Utilisateur();

        // création d'un formulaire
        $form = $this->createForm(RegistrationFormType::class, $user);

        // m.a.j du form avec les donnée collecter et renvoie si le form et true ou false 
        $form->handleRequest($request);

        // si le form est soumit et valid
        if ($form->isSubmitted() && $form->isValid()) {

            // mdp stocké de manière sécuriser (haché)
            $user->setPassword(

                $userPasswordHasher->hashPassword(

                    // l'entité ou le mdp sera associé
                    $user,

                    // récupère le mdp non haché saisies par l'utilisateur
                    $form->get('plainPassword')->getData()
                )
            );

            // indique l'entité
            $entityManager->persist($user);

            // enregistrement de l'entité dans la base de donnée
            $entityManager->flush();

            // création d'un mail de confirmation après inscription
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,

                // new instance TemplateEmail
                (new TemplatedEmail())
                    // expéditeur
                    ->from(new Address('souare@gmail.com', 'ibrahima'))
                    // destinataire
                    ->to($user->getEmail())
                    // object
                    ->subject('Please Confirm your Email')
                    // utilise le contenue html comme un lien par exemple
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // redirection sur la page 
            return $this->redirectToRoute('app_accueil');
        }

        // renvoie une reponse contenant une vue 
        return $this->render('registration/register.html.twig', [

            // un form prèt à être afficher
            'registrationForm' => $form->createView(),
        ]);
    }

    // url quand l'utilisateur click sur le lien
    #[Route('/verify/email', name: 'app_verify_email')]

    /*************************************************************************
     * REQUEST: TOUTES LES INFOS    /    TRANSLATOR: TRADUCTION DES MESSAGE 
    *************************************************************************/

    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        // vérifie si l'utilisateur est entièrement authentifié.
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // si c'est pas le cas il sera capturer dans le blocs catch
        try {
            
            // cette methode valider le lien de confirmation envoyé par e-mail. Elle marque l'utilisateur comme vérifié 
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());

        } catch (VerifyEmailExceptionInterface $exception) {

            // affiche des notifications ou des messages à l'utilisateur
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            // redirection 
            return $this->redirectToRoute('app_register');
        }

        // affiche des notifications ou des messages à l'utilisateur
        $this->addFlash('success', 'Your email address has been verified.');

        // redirection
        return $this->redirectToRoute('app_register');
    }
}
