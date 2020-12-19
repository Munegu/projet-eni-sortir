<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationLieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Nom du lieu'
            ])
            ->add('rue', TextType::class,[
                'label'=>'Nom de la rue'
            ])
            ->add('lattitude', NumberType::class,[
                'label'=>'lattitude'
            ])
            ->add('longitude', NumberType::class,[
                'label'=>'longitude'
            ])
            ->add('ville', EntityType::class, [
                'class'=>'App\Entity\Ville',
                'choice_label'=>'nom',
                'label'=>'Ville'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
