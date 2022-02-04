<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Dispensary;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
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
     * @Groups({"get_collection"}, {"get_order"}, {"get_patient"})
     * @Groups({"get_order"})
     * @Groups({"get_patient"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"get_patient"})
     * @Groups({"get_collection"}, {"get_patient"})
     * @Groups({"get_order"})
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_patient"})
     */
    private $age;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\Unique
     * @Assert\Positive
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_patient"})
     */
    private $vitalCardNumber;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\Unique
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_patient"})
     */
    private $mutuelleNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_order"})
     */
    private $other;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     * @Groups({"get_order"})
     * 
     */
    private $vitalCardFile;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     * @Groups({"get_order"})
     * 
     */
    private $mutuelleFile;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="patient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_patient"})
     * @Groups({"get_order"})
     * 
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="patient", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get_collection"})
     * 
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=Dispensary::class, inversedBy="patients")
     * @ORM\JoinColumn(nullable=true)
     */
    private $dispensary;

    public function __construct()
    {
        $this->user = new User(); 
        $this->orders = new ArrayCollection();
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

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrders(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setPatient($this);
        }

        return $this;
    }

    public function removeOrders(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getPatient() === $this) {
                $order->setPatient(null);
            }
        }

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
