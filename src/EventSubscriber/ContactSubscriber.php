<?php

namespace App\EventSubscriber;

use App\Entity\Contact;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactSubscriber implements EventSubscriber
{
    private $mailer;

    public function __construct (MailerInterface $mailer)
    {
        $this -> mailer = $mailer;
    }

    public function getSubscribedEvents ()
    {
        //retourne un tableau d'événements (prePersist, postPersist, preUpdate etc...)
        return 
        [
            Events::postPersist,
        ];
    }

    public function postPersist (LifecycleEventArgs $args)
    {
        // $args->getObject() nous retourne l'entité concernée par l'événement postPersist
        $entity = $args -> getObject ();

        // Vérifier si l'entité est un nouvel objet de type Contact;
        // Si l'objet persité n'est pas de type Contact, on ne veut pas que le Subscriber se déclenche!
        if ($entity instanceof \App\Entity\Contact)
        {
            $objet = $entity -> getObject ();
            $message = $entity -> getMessage ();

            // Si l'objet ou le text du message contiennent le mot "rgpd", le Subscriber enverra un email à l'adresse "admin@velvet.com"
            if (preg_match ("/rgpd\b/i", $objet) || preg_match ("/rgpd\b/i", $message))
            {
                // envoyer un mail à l'admin
                $email = (new Email ())

                    -> from ('souare@gmail.com')
                    -> to('admin@velvet.com')
                    -> subject ('Alert RGPD')
                    -> text ("Un nouveau message en rapport avec la loi sur les RGPD vous a été envoyer!
         
                        L'id du message : " . $entity -> getId () . 
                        "\n Objet du message : " . $entity -> getObject () . 
                        "\n Texte du message : " . $entity -> getMessage ()
                    )
                ;

                $this -> mailer -> send ($email);

            }

        }

    }

}
