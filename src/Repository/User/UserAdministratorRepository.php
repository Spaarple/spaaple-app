<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\UserAdministrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserAdministrator>
 *
 * @method UserAdministrator|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAdministrator|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAdministrator[]    findAll()
 * @method UserAdministrator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAdministratorRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAdministrator::class);
    }
}
