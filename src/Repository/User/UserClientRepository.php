<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\UserClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserClient>
 *
 * @method UserClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserClient[]    findAll()
 * @method UserClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserClientRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserClient::class);
    }
}
