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
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BulkContact::class);
    }
}
