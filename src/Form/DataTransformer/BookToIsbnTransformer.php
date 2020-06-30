<?php


namespace App\Form\DataTransformer;


use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class BookToIsbnTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * BookToIsbnTransformer constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @inheritDoc
     */
    public function transform($book)
    {;
        /** @var Book $book */
        if (null === $book) {
            return '';
        }

        return $book->getIsbn();
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($isbn)
    {
        $book = $this->entityManager
            ->getRepository(Book::class)
            ->findOneBy(['isbn' => $isbn])
        ;

        if (null === $book) {
            throw new TransformationFailedException("Error");
        }

        return $book;
    }
}