<?php

namespace App\Form;

use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder



            ->add('nom', TextType::class, [
                'label'=>'Nom',
                'attr'=>[
                    'placeholder'=>'Nom de la sortie'
                ]
            ])

            ->add('dateHeureDebut', DateTimeType::class, [
                'label'=>'Date et heure de la sortie',
                'date_widget'=>'choice',
                'date_label' => 'Starts On',
                'format'=>'m/d/Y HH:i',
                'placeholder' => [
                    'day' => 'Jours','month' => 'Mois', 'year' => 'Année',
                       'hour' => 'Heure', 'minute'=>'Minute'

                ]
            ])

            ->add('dateLimiteInscription', DateTimeType::class, [
                'label'=>"Date limite d'inscription",
                'format'=>'d/m/Y',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jours'
                ]
            ])

            ->add('nbInscriptionMax', IntegerType::class, [
                'label'=>'Nombre de places',

                'attr'=>[
                    'min'=>1,
                    'max'=>100,
                    'placeholder'=>"Nombre d'inscrits maximum"
                ]
            ])

            ->add('duree', IntegerType::class, [
                'label'=>'Durée (en minutes)',
                'attr'=>[
                    "min"=>10,
                    'placeholder'=>"Durée de l'évenement en minutes"
                ]
            ])

            ->add('infosSortie', TextareaType::class,[
                'label'=>'Description et infos',
                'attr'=>[
                    'placeholder'=>"Informations sur la sortie"
                ]

            ])

            ->add('lieu',EntityType::class, [
                'label'=>'Lieu',
                'class'=>'App\Entity\Lieu',
                'choice_label'=>'nom'
            ])

//            ->add('lieu', LieuType::class,[
//                'required'=>false,
//            ])

            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('publier', SubmitType::class, ['label' => 'Publier la sortie'])





//            ->add( 'nouveauLieu',LieuType::class, [
//                'mapped'=>false,
//                'required'=>false
//            ])




        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
