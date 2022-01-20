<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"get_collection", "get_pharmacist", "get_address", "get_driver"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"get_collection", "get_pharmacist", "get_driver"})
     * 
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups({"get_collection", "get_pharmacist", "get_driver"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_collection", "get_pharmacist", "get_driver"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank
     * @Groups({"get_collection", "get_pharmacist", "get_driver"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     * @Groups({"get_collection", "get_pharmacist"})
     */
    private $isAdmin;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"get_pharmacist"})
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity=Patient::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"get_pharmacist"})
     */
    private $patient;

    /**
     * @ORM\OneToOne(targetEntity=Driver::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"get_pharmacist"})
     */
    private $driver;

    /**
     * @ORM\OneToOne(targetEntity=Pharmacist::class, mappedBy="user", cascade={"persist", "remove"})
     * 
     */
    private $pharmacist;

    public function __construct()
    {
        $this->address = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->address->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(Patient $patient): self
    {
        // set the owning side of the relation if necessary
        if ($patient->getUser() !== $this) {
            $patient->setUser($this);
        }

        $this->patient = $patient;

        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(Driver $driver): self
    {
        // set the owning side of the relation if necessary
        if ($driver->getUser() !== $this) {
            $driver->setUser($this);
        }

        $this->driver = $driver;

        return $this;
    }

    public function getPharmacist(): ?Pharmacist
    {
        return $this->pharmacist;
    }

    public function setPharmacist(Pharmacist $pharmacist): self
    {
        // set the owning side of the relation if necessary
        if ($pharmacist->getUser() !== $this) {
            $pharmacist->setUser($this);
        }

        $this->pharmacist = $pharmacist;

        return $this;
    }
}
