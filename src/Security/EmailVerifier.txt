<?php

namespace App\Security;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/*****************************************************************************************************************    
*                                           VERIFICATIONS EMAIL
*****************************************************************************************************************/
class EmailVerifier
{
    
    /*******************************************************************    
    * DEPENDANCE QUI IMPLÉMENTE DES CLASSES
    *******************************************************************/

    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    /*******************************************************************    
    * ENVOIE D'EMAIL DE CONFIRMATION ET VÉRIFICATION
    *******************************************************************/

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void
    {
        // va contenir les composants de la signature et la générer
        $signatureComponents = $this->verifyEmailHelper->generateSignature(

            // la route du lien l'ors du clique
            $verifyEmailRouteName,
            
            // id utilisateur pour l'identifier 
            $user->getId(),

            // email utilisateur qui sera aussi utilisé
            $user->getEmail()
        );

        // recupère le contexte actuel
        $context = $email->getContext();

        // ajoute l'url signer len verification email
        $context['signedUrl'] = $signatureComponents->getSignedUrl();

        // traduit les message d'expiration dans template email
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();

        // inclue données d'expiration du mail
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        // m.a.j email
        $email->context($context);

        // envoie email
        $this->mailer->send($email);
    }

    /** // gestion confirmations email
     * @throws VerifyEmailExceptionInterface
    */

    /*******************************************************************    
    * CONFIRMATION UTILISATEUR 
    *******************************************************************/
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        // verification, url, utilisateur, email, validité signature
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        // m.a.j de isVerified
        $user->setIsVerified(true);

        // m.a.j base de données
        $this->entityManager->persist($user);

        // enregistrer dans la base de données
        $this->entityManager->flush();
    }

}
