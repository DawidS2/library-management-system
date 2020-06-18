<?php

namespace App\Entity;

use App\Repository\SpecimenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpecimenRepository::class)
 */
class Specimen
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="specimens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @ORM\Column(type="boolean")
     */
    private $forRent;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="Specimen")
     */
    private $rents;

    public function __construct()
    {
        $this->rents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getForRent(): ?bool
    {
        return $this->forRent;
    }

    public function setForRent(bool $forRent): self
    {
        $this->forRent = $forRent;

        return $this;
    }

    /**
     * @return Collection|Rent[]
     */
    public function getRents(): Collection
    {
        return $this->rents;
    }

    public function addRent(Rent $rent): self
    {
        if (!$this->rents->contains($rent)) {
            $this->rents[] = $rent;
            $rent->setSpecimen($this);
        }

        return $this;
    }

    public function removeRent(Rent $rent): self
    {
        if ($this->rents->contains($rent)) {
            $this->rents->removeElement($rent);
            // set the owning side to null (unless already changed)
            if ($rent->getSpecimen() === $this) {
                $rent->setSpecimen(null);
            }
        }

        return $this;
    }
}
