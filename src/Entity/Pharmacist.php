<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PharmacistRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=PharmacistRepository::class)
 */
class Pharmacist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * @Groups({"get_pharmacist"})
     * @Groups ({"get_collection"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"get_collection"},{"get_pharmacist"})
     * @Groups({"get_pharmacist"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * 
     * 
     */
    private $rppsNumber;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"get_collection"},{"get_pharmacist"})
     * @Groups({"get_pharmacist"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * 
     * 
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="pharmacist", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_order"})
     * @Groups({"get_pharmacist"})
     * @Groups({"get_pharmacists"})
     * 
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="pharmacist", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get_pharmacist"})
     * @Groups({"get_collection"})
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=Dispensary::class, inversedBy="pharmacist",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get_pharmacist"})
     * @Groups({"get_pharmacists"})
     */
    private $dispensary;

    public function __construct()
    {
        $this->user = new User; 
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

    public function getRppsNumber(): ?int
    {
        return $this->rppsNumber;
    }

    public function setRppsNumber(int $rppsNumber): self
    {
        $this->rppsNumber = $rppsNumber;

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
            $order->setPharmacist($this);
        }

        return $this;
    }

    public function removeOrders(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getPharmacist() === $this) {
                $order->setPharmacist(null);
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

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setPharmacist($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getPharmacist() === $this) {
                $order->setPharmacist(null);
            }
        }

        return $this;
    }
}
