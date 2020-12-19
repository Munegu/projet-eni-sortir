<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ville', EntityType::class,[
                'class'=>"App\Entity\Ville",
                'choice_label'=>'nom'
            ])



            ->add('nom', ChoiceType::class, [
                'label'=>'Lieu',
                'choices'=>[
                    'Gymnase Léquier'=>'Gymnase Léquier',
                    'Stade Vélodrome'=>'Stade Vélodrome',
                    'Campus de Beaulieu'=>'Campus de Beaulieu',
                    'Bercy'=>'Bercy'
                ]

            ])

            ->add('lattitude', TextType::class)
            ->add('longitude', TextType::class)




        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
