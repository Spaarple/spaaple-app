<?php

namespace App\Entity;

use App\Repository\EstimateDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EstimateDataRepository::class)]
class EstimateData
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $experiment = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $prepayment = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $profit = null;

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getExperiment(): ?string
    {
        return $this->experiment;
    }

    /**
     * @param string $experiment
     * @return $this
     */
    public function setExperiment(string $experiment): static
    {
        $this->experiment = $experiment;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrepayment(): ?float
    {
        return $this->prepayment;
    }

    /**
     * @param float $prepayment
     * @return $this
     */
    public function setPrepayment(float $prepayment): static
    {
        $this->prepayment = $prepayment;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getProfit(): ?float
    {
        return $this->profit;
    }

    /**
     * @param float $profit
     * @return $this
     */
    public function setProfit(float $profit): static
    {
        $this->profit = $profit;

        return $this;
    }
}
