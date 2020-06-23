<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Rent;
use App\Entity\Specimen;
use App\Form\DataTransformer\IdToUserTransformer;
use App\Form\Model\RentFormModel;
use App\Repository\BookRepository;
use Cassandra\Date;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RentType extends AbstractType
{
    /**
     * @var IdToUserTransformer
     */
    private $transformer;
    /**
     * @var BookRepository
     */
    private $bookRepository;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;


    /**
     * RentType constructor.
     * @param IdToUserTransformer $transformer
     * @param BookRepository $bookRepository
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(IdToUserTransformer $transformer, BookRepository $bookRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->transformer = $transformer;
        $this->bookRepository = $bookRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'choice_label' => 'title',
                'mapped' => false,
                'attr' => ['class' => 'isbn']
            ])
            ->add('reader', TextType::class, [
            ])
        ;
        if (null !== $builder->getData() && null !== $builder->getData()->getId()) {
            $builder->add('rentTo');
        }

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                /** @var Rent $data */
                $data = $event->getData();

                if (!$data) return;
                $book = (null === $data->getSpecimen()) ? null : $data->getSpecimen()->getBook();
                $form->get('book')->setData($book);
                $this->formModifier($form, $book);
            }
        );

        $builder->get('book')->addEventListener(FormEvents::POST_SUBMIT,
        function (FormEvent $event) {
            $form = $event->getForm();
            $data = $form->getData();

            $this->formModifier($form->getParent(), $data);
        });

        $builder->get('reader')
            ->addModelTransformer($this->transformer)
        ;
    }

    private function formModifier(FormInterface $form, ?Book $book): void
    {
        $disabled = false;
        if (null === $book) {
            $specimens = [];
            $disabled = true;
        }else{
            $specimens = $book->getSpecimens();
        }

        $form->add('Specimen', EntityType::class, [
            'class' => Specimen::class,
            'choices' => $specimens,
            'choice_label' => 'id',
            'placeholder' => 'Wybierz egzemplarz',
            'disabled' => $disabled,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rent::class,
        ]);
    }
}
