<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RentRepository::class)
 */
class Rent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Specimen::class, inversedBy="rents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Specimen;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reader;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="Wprowadź poprawną datę")
     */
    private $rentAt;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="Wprowadź poprawną datę")
     */
    private $rentTo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReturned;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecimen(): ?Specimen
    {
        return $this->Specimen;
    }

    public function setSpecimen(?Specimen $Specimen): self
    {
        $this->Specimen = $Specimen;

        return $this;
    }

    public function getReader(): ?User
    {
        return $this->reader;
    }

    public function setReader(?User $reader): self
    {
        $this->reader = $reader;

        return $this;
    }

    public function getRentAt(): ?\DateTimeInterface
    {
        return $this->rentAt;
    }

    public function setRentAt(\DateTimeInterface $rentAt): self
    {
        $this->rentAt = $rentAt;

        return $this;
    }

    public function getRentTo(): ?\DateTimeInterface
    {
        return $this->rentTo;
    }

    public function setRentTo(\DateTimeInterface $rentTo): self
    {
        $this->rentTo = $rentTo;

        return $this;
    }

    public function getIsReturned(): ?bool
    {
        return $this->isReturned;
    }

    public function setIsReturned(bool $isReturned): self
    {
        $this->isReturned = $isReturned;

        return $this;
    }
}
