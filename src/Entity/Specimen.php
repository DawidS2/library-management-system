<?php

namespace App\Entity;

use App\Repository\SpecimenRepository;
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
}
