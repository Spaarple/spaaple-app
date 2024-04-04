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
    #[ORM\OneToMany(targetEntity: Estimate::class, mappedBy: 'userClient', orphanRemoval: true)]
    private Collection $estimates;

    public function __construct()
    {
        parent::__construct();
        $this->setRoles([Role::ROLE_CLIENT->name]);
        $this->estimates = new ArrayCollection();
    }

    /**
     * @return Collection<int, Estimate>
     */
    public function getEstimates(): Collection
    {
        return $this->estimates;
    }

    /**
     * @param Estimate $estimate
     * @return $this
     */
    public function addEstimate(Estimate $estimate): static
    {
        if (!$this->estimates->contains($estimate)) {
            $this->estimates->add($estimate);
            $estimate->setUserClient($this);
        }

        return $this;
    }

    /**
     * @param Estimate $estimate
     * @return $this
     */
    public function removeEstimate(Estimate $estimate): static
    {
        if ($this->estimates->removeElement($estimate) && $estimate->getUserClient() === $this) {
            $estimate->setUserClient(null);
        }

        return $this;
    }
}
