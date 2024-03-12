<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Enum\Role;
use App\Repository\User\UserAdministratorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAdministratorRepository::class)]
class UserAdministrator extends AbstractUser
{
    public function __construct()
    {
        parent::__construct();
        $this->setRoles([Role::ROLE_ADMINISTRATOR->name]);
    }
}
