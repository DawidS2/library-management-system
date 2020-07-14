<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Rent;
use App\Entity\Specimen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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

    public function findBookById(int $id)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());

        $rsm->addEntityResult(Book::class, 'b');
        $rsm->addFieldResult('b', 'book_id', 'id');
        $rsm->addFieldResult('b', 'title', 'title');
        $rsm->addFieldResult('b', 'description', 'description');
        $rsm->addFieldResult('b', 'pages', 'numberOfPages');
        $rsm->addFieldResult('b', 'isbn', 'isbn');

        $rsm->addJoinedEntityResult(Specimen::class, 's', 'b', 'specimens');
        $rsm->addFieldResult('s', 'specimen_id', 'id');
        $rsm->addFieldResult('s', 'for_rent', 'forRent');

        $rsm->addJoinedEntityResult(Rent::class, 'r', 's', 'rents');
        $rsm->addFieldResult('r', 'rent_id', 'id');
        $rsm->addFieldResult('r', 'rent_to', 'rentTo');
        $rsm->addFieldResult('r', 'is_returned', 'isReturned');

        $rsm->addJoinedEntityResult(Author::class, 'a', 'b', 'authors');
        $rsm->addFieldResult('a', 'name', 'name');
        $rsm->addFieldResult('a', 'surname', 'surname');
        $rsm->addFieldResult('a', 'author_id', 'id');

        $rsm->addJoinedEntityResult(Category::class, 'c', 'b', 'categories');
        $rsm->addFieldResult('c', 'category_name', 'name');
        $rsm->addFieldResult('c', 'category_id', 'id');


        $query = $this->getEntityManager()->createNativeQuery(
            '
            SELECT b.id as book_id, b.title, b.description as description, b.number_of_pages as pages, b.isbn, s.id as specimen_id, s.for_rent, r.id as rent_id, r.rent_to as rent_to, r.is_returned, a.name, a.surname, a.id as author_id, c.id as category_id, c.name as category_name
            FROM book b
            LEFT JOIN book_author ba
            ON ba.book_id = b.id
            LEFT JOIN author a 
            ON ba.author_id = a.id
            LEFT JOIN book_category bc
            ON bc.book_id = b.id
            LEFT JOIN category c 
            ON c.id = bc.category_id
            INNER JOIN specimen s
            ON b.id = s.book_id
            LEFT JOIN rent r
            ON s.id=r.specimen_id
                AND r.id = (
                    SELECT rent.id
                    FROM rent
                    WHERE rent.specimen_id = s.id
                    ORDER BY rent.id
                    DESC LIMIT 1
                )
            WHERE b.id = ?', $rsm);
        $query->setParameter(1, $id);
//        return $query->getResult();
        return $query->getOneOrNullResult();
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
