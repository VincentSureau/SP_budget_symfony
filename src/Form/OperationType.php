<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Operation;
use App\Entity\PaymentMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\PropertyInfo\Type;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',MoneyType::class,[
                "label" => "Montant"
            ])
            ->add('comment',TextareaType::class,[
                "label" => "Commentaire"
            ])
            ->add('date',DateType::class,[
                "label" => "Date",
                'widget' => 'single_text',
            ])
            ->add('type',ChoiceType::class,[
                "label" => "Type d'opération",
                'required' => true,
                'choices' => Operation::getTypes(),
                'choice_label' => function ($choice) {
                    return Operation::getTypeName($choice);
                },
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                "label" => "Type de catégorie",
            ])
            ->add('paymentMethod',EntityType::class,[
                'class' => PaymentMethod::class,
                "label" => "Moyen de paiement",
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
