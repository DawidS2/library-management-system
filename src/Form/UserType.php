<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\CustomPasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;

    /**
     * UserType constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('roles', ChoiceType::class, [
                'expanded' => true,
                'choices' => ['Administrator' => 'ROLE_ADMIN'],
                'multiple' => true,
                'label' => 'Typ konta'
                ]);
        }

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ]);


        if ((null === $builder->getData()
                || null === $builder->getData()->getId())
                && !$this->security->isGranted('ROLE_ADMIN') ) {
            $builder
                ->add('plainPassword', CustomPasswordType::class, [
                    'label' => 'Hasło',
                    'mapped' => false
                ]);
        }
        $builder
            ->add('name', TextType::class, [
                'label' => 'Imię'
            ])
            ->add('surname', TextType::class, [
                'label' => 'Nazwisko'
            ])
            ->add('street', TextType::class, [
                'label' => 'Ulica'
            ])
            ->add('city', TextType::class, [
                'label' => 'Miasto'
            ])
            ->add('apartamentNumber', TextType::class, [
                'label' => 'Numer domu'
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Kod pocztowy',
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
