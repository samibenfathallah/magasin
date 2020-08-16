<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Designation',TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('Description',TextareaType::class, ['attr' => ['class' => 'form-control'],'required' => false])
            ->add('Qty',IntegerType ::class, ['attr' => ['class' => 'form-control']])
            ->add('price',MoneyType ::class, ['attr' => ['class' => 'form-control']])
            ->add('Supplier', EntityType::class, [
                'class'        => Supplier::class,
                'choice_label' => 'Designation',
                'multiple'     => false,
                'attr' => ['class' => 'form-control']
              ])
            ->add('Category', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'Designation',
                'multiple'     => false,
                'attr' => ['class' => 'form-control']
              ])
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
