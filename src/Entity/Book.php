<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="Tytuł może zawierać maksymalnie 255 znaków")
//     * @Assert\NotBlank(message="Wpisz tytuł")
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Wpisz poprawną ilość stron")
     * @Assert\Regex(
     *     pattern="/^\d+$/",
     *     match=true,
     *     message="Liczba stron musi być liczbą"
     * )
     */
    private $numberOfPages;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Isbn(type="isbn13")
     */
    private $isbn;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cover;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="books")
     */
    private $authors;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="books")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Specimen::class, mappedBy="book")
     */
    private $specimens;

    /**
     * @ORM\ManyToOne(targetEntity=Publisher::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publisher;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->specimens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNumberOfPages(): ?int
    {
        return $this->numberOfPages;
    }

    public function setNumberOfPages(int $numberOfPages): self
    {
        $this->numberOfPages = $numberOfPages;

        return $this;
    }

    public function getIsbn(): ?int
    {
        return $this->isbn;
    }

    public function setIsbn(int $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Specimen[]
     */
    public function getSpecimens(): Collection
    {
        return $this->specimens;
    }

    public function addSpecimen(Specimen $specimen): self
    {
        if (!$this->specimens->contains($specimen)) {
            $this->specimens[] = $specimen;
            $specimen->setBook($this);
        }

        return $this;
    }

    public function removeSpecimen(Specimen $specimen): self
    {
        if ($this->specimens->contains($specimen)) {
            $this->specimens->removeElement($specimen);
            // set the owning side to null (unless already changed)
            if ($specimen->getBook() === $this) {
                $specimen->setBook(null);
            }
        }

        return $this;
    }

    public function getNonRentedSpecimens()
    {
        $nonRentedSpecimens = [];

        foreach ($this->getSpecimens() as $specimen)
        {
            if ($specimen->getLastRent() === null || $specimen->getLastRent()->getIsReturned() === true && $specimen->getForRent() === true) {
                $nonRentedSpecimens[] = $specimen;
            }
        }

        return $nonRentedSpecimens;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(?Publisher $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }
}
