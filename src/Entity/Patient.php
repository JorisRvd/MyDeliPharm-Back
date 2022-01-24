<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"get_collection"})
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"get_collection"})
     * @Assert\NotBlank
     */
    private $age;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Unique
     * @Assert\Positive
     * 
     */
    private $vitalCardNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Unique
     * @Assert\Positive
     * 
     */
    private $mutuelleNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $other;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"get_collection"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     * @Assert\Url
     */
    private $vitalCardFile;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     * @Assert\Url
     */
    private $mutuelleFile;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="patient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_collection"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, mappedBy="patient", cascade={"persist", "remove"})
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=Dispensary::class, inversedBy="patients")
     * @ORM\JoinColumn(nullable=true)
     */
    private $dispensary;


    public function __construct()
    {
        $this->user = new User; 
    }
    public function __toString()
    {
        return $this->user;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getVitalCardNumber(): ?int
    {
        return $this->vitalCardNumber;
    }

    public function setVitalCardNumber(?int $vitalCardNumber): self
    {
        $this->vitalCardNumber = $vitalCardNumber;

        return $this;
    }

    public function getMutuelleNumber(): ?int
    {
        return $this->mutuelleNumber;
    }

    public function setMutuelleNumber(?int $mutuelleNumber): self
    {
        $this->mutuelleNumber = $mutuelleNumber;

        return $this;
    }

    public function getOther(): ?string
    {
        return $this->other;
    }

    public function setOther(?string $other): self
    {
        $this->other = $other;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getVitalCardFile(): ?string
    {
        return $this->vitalCardFile;
    }

    public function setVitalCardFile(?string $vitalCardFile): self
    {
        $this->vitalCardFile = $vitalCardFile;

        return $this;
    }

    public function getMutuelleFile(): ?string
    {
        return $this->mutuelleFile;
    }

    public function setMutuelleFile(?string $mutuelleFile): self
    {
        $this->mutuelleFile = $mutuelleFile;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOrders(): ?Order
    {
        return $this->orders;
    }

    public function setOrders(?Order $orders): self
    {
        // unset the owning side of the relation if necessary
        if ($orders === null && $this->orders !== null) {
            $this->orders->setPatient(null);
        }

        // set the owning side of the relation if necessary
        if ($orders !== null && $orders->getPatient() !== $this) {
            $orders->setPatient($this);
        }

        $this->orders = $orders;

        return $this;
    }

    public function getDispensary(): ?Dispensary
    {
        return $this->dispensary;
    }

    public function setDispensary(?Dispensary $dispensary): self
    {
        $this->dispensary = $dispensary;

        return $this;
    }

}
