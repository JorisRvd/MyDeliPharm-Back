<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weight')
            ->add('age')
            ->add('vitalCardNumber')
            ->add('mutuelleNumber')
            ->add('other')
            ->add('status')
            ->add('vitalCardFile')
            ->add('mutuelleFile')
            ->add('user')
            ->add('orders')
            ->add('dispensary')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
