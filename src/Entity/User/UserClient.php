<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Estimate;
use App\Enum\Role;
use App\Repository\User\UserClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserClientRepository::class)]
class UserClient extends AbstractUser
{

    public function __construct()
    {
        parent::__construct();
        $this->setRoles([Role::ROLE_CLIENT->name]);
    }
}
