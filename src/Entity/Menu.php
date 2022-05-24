<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vacance;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nuit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deplace;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $handicap;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVacance(): ?int
    {
        return $this->vacance;
    }

    public function setVacance(?int $vacance): self
    {
        $this->vacance = $vacance;

        return $this;
    }

    public function getNuit(): ?int
    {
        return $this->nuit;
    }

    public function setNuit(?int $nuit): self
    {
        $this->nuit = $nuit;

        return $this;
    }

    public function getDeplace(): ?int
    {
        return $this->deplace;
    }

    public function setDeplace(?int $deplace): self
    {
        $this->deplace = $deplace;

        return $this;
    }

    public function getHandicap(): ?int
    {
        return $this->handicap;
    }

    public function setHandicap(?int $handicap): self
    {
        $this->handicap = $handicap;

        return $this;
    }
}
