<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\CustomPasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class);

        if (null === $builder->getData() || null === $builder->getData()->getId()) {
            $builder
                ->add('plainPassword', CustomPasswordType::class, [
                    'mapped' => false
                ]);
        }
        $builder
            ->add('name', TextType::class)
        ->add('surname', TextType::class)
        ->add('street', TextType::class)
        ->add('city', TextType::class)
        ->add('apartamentNumber', TextType::class)
        ->add('zipCode', TextType::class, [
            'help' => 'np. 00-000'
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
