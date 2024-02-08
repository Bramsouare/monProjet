<?php

namespace App\Form;

use App\templates\emails\contact_email;
use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('utilisateur')
            ->add('email')
            ->add('object')
            ->add('message',TextareaType::class, [
                'label'=>'Votre message',     
                // Ajout label et champ optionnel grâce à "required" = false:
                'required'=>false
            ])
            ->add('envoyer', SubmitType::class,[
                'label'=>'Envoyer '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
