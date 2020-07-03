<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\CustomPasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'constraints' => [
                    new UserPassword(
                        [
                            'message' => 'Hasło niepoprawne'
                        ]
                    )
                ]
            ])
            ->add('newPassword', CustomPasswordType::class)
        ;
    }


}
