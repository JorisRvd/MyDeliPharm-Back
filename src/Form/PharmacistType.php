<?php

namespace App\Form;

use App\Entity\Pharmacist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PharmacistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rppsNumber')
            ->add('status')
            ->add('profilPic')
            ->add('user')
            ->add('orders')
            ->add('dispensary')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pharmacist::class,
        ]);
    }
}
