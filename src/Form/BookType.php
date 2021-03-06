<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Tytuł'
            ])
            ->add('numberOfPages', null, [
                'label' => 'Numer stron'
            ])
            ->add('isbn', null, [
                'label' => 'ISBN'
            ])
            ->add('description', null, [
                'label' => 'Opis'
            ])
            ->add('authors', EntityType::class, [
                'label' => 'Autorzy',
                'class' => Author::class,
                'choice_label' =>
                    function(Author $author)
                    {
                        return sprintf("%s %s", $author->getName(), $author->getSurname());
                    },
                'multiple' => true
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Kategorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('publisher', EntityType::class, [
                'label' => 'Wydawnictwo',
                'class' => Publisher::class,
                'choice_label' => 'name',
            ])
        ;

        if (null === $builder->getData() || null === $builder->getData()->getId()) {
            $builder
                ->add('numberOfSpecimen', IntegerType::class, [
                    'mapped' => false,
                    'constraints' => [
                        new PositiveOrZero([
                            'message' => "Wpisz poprawną wartość"
                        ])
                    ]
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
