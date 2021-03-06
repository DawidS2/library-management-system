<?php

namespace App\Repository;

use App\Entity\Rent;
use App\Entity\Specimen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Specimen|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specimen|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specimen[]    findAll()
 * @method Specimen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecimenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specimen::class);
    }

    public static function getLastRent()
    {
        return Criteria::create()
            ->orderBy(['id' => Criteria::DESC])
            ->setMaxResults(1)
            ;
    }

    // /**
    //  * @return Specimen[] Returns an array of Specimen objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Specimen
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
