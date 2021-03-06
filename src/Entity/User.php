<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * @Groups({"get_pharmacist"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * @Groups({"get_pharmacist"})
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"get_collection"}, {"get_pharmacist"}, {"get_driver"}, {"get_patient"})
     * @Groups({"get_order"})
     * @Assert\NotBlank
     * @Groups({"get_pharmacists"})
     * @Groups({"get_pharmacist"})
     * 
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * 
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Groups({"get_collection"}, {"get_pharmacist"}, {"get_driver"}, {"get_patient"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * @Groups({"get_pharmacist"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Groups({"get_collection"}, {"get_pharmacist"}, {"get_driver"}, {"get_patient"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * @Groups({"get_pharmacist"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"get_collection"}, {"get_pharmacist"}, {"get_driver"}, {"get_patient"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * @Groups({"get_pharmacist"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get_collection"}, {"get_pharmacist"}, {"get_driver"}, {"get_patient"})
     */
     
    private $isAdmin;
        
    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"get_collection"})
     * @Groups({"get_pharmacist"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * 
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity=Patient::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"get_collection"})
     */
    private $patient;

    /**
     * @ORM\OneToOne(targetEntity=Driver::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"get_collection"})
     */
    private $driver;

    /**
     * @ORM\OneToOne(targetEntity=Pharmacist::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"get_collection"})
     */
    private $pharmacist;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     * @Groups({"get_collection"}, {"get_pharmacist"}, {"get_driver"}, {"get_patient"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     */
    private $profilPic;

    public function __construct()
    {
        $this->address = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): self
    {
        $this->gender = $gender;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getProfilPic(): ?string
    {
        return $this->profilPic;
    }

    public function setProfilPic(?string $profilPic): self
    {
        $this->profilPic = $profilPic;

        return $this;
    }


    
}
