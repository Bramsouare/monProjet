<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/*****************************************************************************************************
 *                                      CRÉATION FORMULAIRE
*****************************************************************************************************/

class RegistrationFormType extends AbstractType
{

    /*****************************************************************************************************
    * BUILDER: CHAMPS,OPTIONS  /   OPTIONS: PERSONALISATIONS,COMPORTEMENT   /   VOID: RETURN RIEN 
    *****************************************************************************************************/

    public function buildForm (FormBuilderInterface $builder, array $options): void
    {
        $builder

            // ajouter un champs email
            -> add ('email')

            // ajouter un champs case a cocher
            -> add ('agreeTerms', CheckboxType::class, 

                [

                    // non mappé a l'entité et les information ne sont enregistrer
                    'mapped' => false,
                    'label' => "Validation obligatoire",

                    // contrainte de validation
                    'constraints' => 

                    [
                        // si la case n'est pas cochez une alert s'affiche
                        new IsTrue(

                            [
                                'message' => 'You should agree to our terms.',
                            ]
                        ),
                    ],
                ]
            )

            // ajoute un champ passwordType
            -> add ('plainPassword', PasswordType::class, 

                [
                
                    // ce champ n'est pas mappé à l'entité est les info son pas enregistrer
                    'mapped' => false,

                    // désactive l'autocomplétion du mdp
                    'attr' => ['autocomplete' => 'new-password'],

                    // contraintes de validation pour ce champ
                    'constraints' => 
                    [
                        // vérifie si le champs est vide ou pas si c'est vide alert
                        new NotBlank(

                            [
                                'message' => 'Please enter a password',
                            ]
                        ),

                        // la longueurs minimale
                        new Length(
                            
                            [
                                // la longueurs minimale est de 6 si le mdp est plus court alors alert
                                'min' => 6,

                                'minMessage' => 'Your password should be at least {{ limit }} characters',

                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]
                        ),

                    ],

                ]
            )

        ;

    }

    /*****************************************************************************************************
     * OPTIONS PAR DÉFAUT DU FORMULAIRE FUSIONNER AVEC LES OPTIONS DE CRÉATIONS
    *****************************************************************************************************/

    public function configureOptions (OptionsResolver $resolver): void
    {
        // Cette méthode permet de définir les options par défaut du formulaire
        $resolver -> setDefaults(

            [
                /**  les données saisies seront automatiquement transmises à une instance de la classe Utilisateur, 
                * ce qui facilite la liaison des champs du formulaire aux propriétés de l'objet Utilisateur
                */

                'data_class' => Utilisateur::class,
            ]
        );

    }

}

