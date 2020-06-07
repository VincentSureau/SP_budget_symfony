<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Operation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Type;


class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',TextType::class,[
                "label" => "Montant"
            ])
            ->add('comment',TextType::class,[
                "label" => "Libellé"
            ])
            ->add('date',TextType::class,[
                "label" => "Date"
            ])
            ->add('type',ChoiceType::class,[
                "label" => "Type d'opération",
                'choices' => [
                    'Crédit' => true,
                    'Débit' => false,
                ],
            ])


            ->add('paymentMethod',TextType::class,[
                "label" => "Moyen de paiement"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
