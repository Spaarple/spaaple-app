<?php

namespace App\Entity;

use App\Entity\User\UserClient;
use App\Enum\CMS;
use App\Enum\Complexity;
use App\Enum\NumberPage;
use App\Repository\EstimateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EstimateRepository::class)]
class Estimate
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'estimates')]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserClient $userClient = null;

    #[ORM\Column(type: Types::JSON)]
    private array $integration = [];

    #[ORM\Column(type: Types::STRING, length: 255, enumType: CMS::class)]
    private CMS $cms;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: NumberPage::class)]
    private NumberPage $page;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: Complexity::class)]
    private Complexity $complexity;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $result = null;

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return UserClient|null
     */
    public function getUserClient(): ?UserClient
    {
        return $this->userClient;
    }

    /**
     * @param UserClient|null $userClient
     * @return $this
     */
    public function setUserClient(?UserClient $userClient): static
    {
        $this->userClient = $userClient;

        return $this;
    }

    /**
     * @return array
     */
    public function getIntegration(): array
    {
        return $this->integration;
    }

    /**
     * @param array $integration
     * @return $this
     */
    public function setIntegration(array $integration): static
    {
        $this->integration = $integration;

        return $this;
    }

    /**
     * @return CMS
     */
    public function getCms(): CMS
    {
        return $this->cms;
    }

    /**
     * @param CMS $cms
     * @return $this
     */
    public function setCms(CMS $cms): static
    {
        $this->cms = $cms;

        return $this;
    }

    /**
     * @return NumberPage
     */
    public function getPage(): NumberPage
    {
        return $this->page;
    }

    /**
     * @param NumberPage $page
     * @return $this
     */
    public function setPage(NumberPage $page): static
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return Complexity|null
     */
    public function getComplexity(): ?Complexity
    {
        return $this->complexity;
    }

    /**
     * @param Complexity $complexity
     * @return $this
     */
    public function setComplexity(Complexity $complexity): static
    {
        $this->complexity = $complexity;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getResult(): ?int
    {
        return $this->result;
    }

    /**
     * @param int $result
     * @return $this
     */
    public function setResult(int $result): static
    {
        $this->result = $result;

        return $this;
    }
}
