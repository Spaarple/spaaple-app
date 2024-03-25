<?php

namespace App\Repository;

use App\Entity\Estimate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Estimate>
 *
 * @method Estimate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estimate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estimate[]    findAll()
 * @method Estimate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimateRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estimate::class);
    }
}
