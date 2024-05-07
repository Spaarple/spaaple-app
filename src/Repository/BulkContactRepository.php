<?php

namespace App\Repository;

use App\Entity\BulkContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BulkContact>
 *
 * @method BulkContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method BulkContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method BulkContact[]    findAll()
 * @method BulkContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BulkContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BulkContact::class);
    }

    //    /**
    //     * @return BulkContact[] Returns an array of BulkContact objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BulkContact
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
