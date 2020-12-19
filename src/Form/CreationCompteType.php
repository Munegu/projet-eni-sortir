<?php

namespace App\Form;

use App\Entity\Participant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationCompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('nom', TextType::class,[
                'label'=>"Nom",
                'attr'=>[
                    'placeholder'=>"Nom",
                ]
            ])

            ->add('prenom', TextType::class,[
                'required'=>true,
                'label'=>'Prenom',
                'attr'=>[
                    'placeholder'=>"Prenom"
                ]
            ])

            ->add('telephone', TextType::class,[
                'label'=>"Téléphone",
                'attr'=>[
                    'placeholder'=>"Numero de téléphone"
                ]
            ])
            ->add('Email', EmailType::class,[
                'label'=>"Email",
                'attr'=>[
                    'placeholder'=>"Votre Email"
                ]
            ])

            ->add('siteRattache', EntityType::class,[
                'class'=>"App\Entity\Site",
                'required'=>true,
                'choice_label'=>"nom",
                'placeholder'=>"Sélectionner votre site",
                'expanded'=>false,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nom', 'desc');
                }
            ])

            ->add('username',TextType::class,[
                'required'=>true,
                'label'=>'Pseudo',
                    'attr'=>[
                        'placeholder'=>'Votre Pseudo'
                    ]
                ]
            )

            ->add('password', RepeatedType::class, [
                'label'=>'Mot de passe',
                'type' => PasswordType::class,
                'invalid_message' => "Vous n'avez pas confirmé le même mot de passe",
                'first_options' => [
                    'required'=>true,
                    'label' => 'Mot de passe'
                ],
                'second_options' => [
                    'required'=>true,
                    'label' => "Confirmation du mot de passe"
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
