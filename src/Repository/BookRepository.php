<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findAllWithSearch(string $query)
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.authors', 'a')
            ->addSelect('a')
            ->andWhere('b.title LIKE :query OR b.isbn LIKE :query OR a.name LIKE :query OR a.surname LIKE :query')
            ->setParameter(':query', '%' . $query . '%')
            ->getQuery()
            ->getResult()
            ;

        return $qb;
    }

//    private function findByField(callable $callback)
//    {
//        $qb =  $this->createQueryBuilder('b');
//
//        return $callback($qb)
//            ->leftJoin('b.authors', 'authors')
//            ->addSelect('authors')
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//
//    public function findByIsbn(int $isbn)
//    {
//        return $this->findByField(
//            function (QueryBuilder $queryBuilder) use ($isbn) {
//                return $queryBuilder
//                    ->andWhere('b.isbn LIKE :query')
//                    ->setParameter(':query', '%' . $isbn . '%')
//                    ;
//            }
//        );
//    }
//
//    public function findByTitle(string $title)
//    {
//        return $this->findByField(
//            function (QueryBuilder $queryBuilder) use ($title) {
//                return $queryBuilder
//                    ->andWhere('b.title LIKE :query')
//                    ->setParameter(':query', '%' . $title . '%')
//                    ;
//            }
//        );
//    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
