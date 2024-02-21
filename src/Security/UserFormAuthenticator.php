<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


/********************************************************************************************************************************************************************************************************
 *                                               GESTIONS D'AUTHENTIFICATION DE L'UTILISATEUR L'ORS DE LA SOUMISSION DE FORMULAIRE
********************************************************************************************************************************************************************************************************/

class UserFormAuthenticator extends AbstractLoginFormAuthenticator
{
    // stocke et de récupérer le chemin cible vers lequel rediriger l'utilisateur après son authentification
    use TargetPathTrait;

    // rediriger l'utilisateur s'il tente d'accéder à une page protégée sans être authentifié
    public const LOGIN_ROUTE = 'app_accueil';

    
    /*****************************************************************************************************
     * GÉNÉRATION D'URL DANS SYMFONY QUAND UN OBJECT USERFORM... EST INSTANCIÉ
    *****************************************************************************************************/

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }


    /*****************************************************************************************************
     * UTILISATION DE REQUÊTE HTTP POUR RETOUNER UN OBJECT PASSPORT
    *****************************************************************************************************/

    public function authenticate(Request $request): Passport
    {
        // extrait la valeur du champ email grâce à la requête HTTP. Si aucun champ email n'est trouvé, une chaîne vide est utilisée comme valeur par défaut
        $email = $request->request->get('email', '');

        // Cette ligne stock la dernière adresse email utilisée ce qui permet de pré-remplir le champ
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // objet contenant les informations d'identification de l'utilisateur (comme son adresse email et son mot de passe)
        return new Passport(

            // récupérer l'utilisateur à partir de son identifiant (dans ce cas, son adresse email)
            new UserBadge($email),

            // PasswordCredentials contient le mot de passe brut de l'utilisateur
            new PasswordCredentials($request->request->get('password', '')),
            [
                // Le badge CsrfTokenBadge vérifie le jeton CSRF envoyé avec la requête pour se prévenir contre les attaques CSRF.
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                
                // Le badge RememberMeBadge permet à l'utilisateur de rester connecté en activant la fonctionnalité "Se souvenir de moi" lors de l'authentification
                new RememberMeBadge(),
            ]
        );
    }

    /*****************************************************************************************************
     * GESTIONS ACTION QUAND UNE AUTHENTIFICATION REUSSIT
    *****************************************************************************************************/

    // $request: accéder à différentes informations sur la requête, comme les données soumises par le formulaire
    // $token: le jeton d'authentification de l'utilisateur. Il contient des informations sur l'utilisateur authentifié
    // $firewallName: pare-feu utilisé pour l'authentification. Cela peut être utile si vous avez plusieurs pare-feux dans votre application

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // vérifie si une URL de redirection cible a été récupérée avec succès à partir de la session.
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {

            // l'utilisateur sera redirigé vers la page qu'il avait initialement demandée avant d'être redirigé vers la page de connexion.
            return new RedirectResponse($targetPath);
        }

        // si aucun url est trouvé il sera redirigé vers la page profil
        return new RedirectResponse($this->urlGenerator->generate('app_accueil'));

        // si aucun cas n'est géré, l'exception TODO est levée avec un message indiquant qu'une redirection valide doit être fournie à l'intérieur du fichier
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    /*****************************************************************************************************
     * REDIRECTION SI TENTATIVE DE CONNECTION SANS AUTHENTIFICATION SUR PAGE SÉCURISÉ
    *****************************************************************************************************/
    
    protected function getLoginUrl(Request $request): string
    
    {

        // $this->urlGenerator: utilisé pour générer des URLs dans Symfony
        // generate(self::LOGIN_ROUTE): prend en argument le nom de la route vers laquelle vous souhaitez générer l'URL.
        // LOGIN_ROUTE: est utilisée pour garantir la cohérence du nom de la route et éviter les erreurs de frappe
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
