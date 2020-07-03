<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 */
class Author
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Wpisz imię")
     * @Assert\Length(max="100", maxMessage="Imię może zawierać maxymalnie 100 znaków")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Imię nie może zawierać cyfr"
     * )
     */
    private $name;

    /**
//     * @Assert\NotBlank(message="Wpisz nazwisko")
     * @Assert\Length(max="100", maxMessage="Nazwisko może zawierać maksymalnie 100 znaków")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Nazwisko nie może zawierać cyfr"
     * )
     * @ORM\Column(type="string", length=100)
     */
    private $surname;

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, mappedBy="authors")
     */
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
            $book->removeAuthor($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName() . ' ' . $this->getSurname();
    }
}
