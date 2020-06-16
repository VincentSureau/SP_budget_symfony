<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FilterOperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateStart', DateType::class, [
                'label' => 'Du',
                'widget' => 'single_text',
                'data' => new \DateTime('first day of this month'),
                'attr' => [
                    "onChange" => 'this.form.submit();'
                ]
            ])
            ->add('dateEnd', DateType::class, [
                'label' => 'Au',
                'widget' => 'single_text',
                'data' => new \DateTime('now'),
                'attr' => [
                    "onChange" => 'this.form.submit();'
                ]
            ])
            ->setMethod('GET')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
