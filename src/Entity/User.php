<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(max="180", maxMessage="Email może zawierać maksymalnie 180 znaków")
     * @Assert\Email(message="Wpisz poprawny adres email")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="reader")
     */
    private $rents;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ta wartość nie może być pusta")
     * @Assert\Length(max="100", maxMessage="Imię może zawierać maksymalnie 100 znaków")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
//     * @Assert\NotBlank(message="Ta wartość nie może być pusta")
     * @Assert\Length(max="100", maxMessage="Nazwisko może zawierać maksymalnie 100 znaków")
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ta wartość nie może być pusta")
     * @Assert\Length(max="100", maxMessage="Ulica może zawierać maksymalnie 100 znaków")
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message="""Ta wartość nie może być pusta")
     * @Assert\Length(max="10", maxMessage="Numer domu może zawierać maksymalnie 10 znaków")
     */
    private $apartamentNumber;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ta wartość nie może być pusta")
     * @Assert\Length(max="100", maxMessage="Miasto może zawierać maksymalnie 100 znaków")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=6)
     * @Assert\Regex(
     *     pattern="/^[0-9]{2}-[0-9]{3}$/",
     *     match=true,
     *     message="Wpisz poprawny kod"
     * )
     */
    private $zipCode;

    public function __construct()
    {
        $this->rents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $rent->setReader($this);
        }

        return $this;
    }

    public function removeRent(Rent $rent): self
    {
        if ($this->rents->contains($rent)) {
            $this->rents->removeElement($rent);
            // set the owning side to null (unless already changed)
            if ($rent->getReader() === $this) {
                $rent->setReader(null);
            }
        }

        return $this;
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getApartamentNumber(): ?string
    {
        return $this->apartamentNumber;
    }

    public function setApartamentNumber(string $apartamentNumber): self
    {
        $this->apartamentNumber = $apartamentNumber;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
