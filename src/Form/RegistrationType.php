<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder
            ->add('email',TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('username',TextType::class, ['attr' => ['class' => 'form-control'],'required' => true])
            ->add('password',PasswordType::class, ['attr' => ['class' => 'form-control'],'required' => true])
            ->add('validpassword',PasswordType::class, ['attr' => ['class' => 'form-control'],'label' => 'Enter your password again','required' => true])
            ->add('roles',ChoiceType::class,['choices'=>['User'=>'ROLE_USER','Admin'=>'ROLE_ADMIN'],'expanded'=>false,'multiple'=>true,'attr' => ['class' => 'form-control'],'label'=>'Roles'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
