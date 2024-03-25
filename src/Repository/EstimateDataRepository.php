<?php

namespace App\Repository;

use App\Entity\EstimateData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstimateData>
 *
 * @method EstimateData|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstimateData|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstimateData[]    findAll()
 * @method EstimateData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimateDataRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstimateData::class);
    }
}
