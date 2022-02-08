<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 */
class Driver
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"get_collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_collection"},{"get_driver"})
     * @Assert\NotBlank
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"get_collection"},{"get_driver"})
     * @Assert\NotBlank
     */
    private $vehicule;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     * @Groups({"get_collection"},{"get_driver"})
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="driver", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * 
     * 
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="driver", cascade={"persist", "remove"})
     * @Groups({"get_collection"})
     * 
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getVehicule(): ?string
    {
        return $this->vehicule;
    }

    public function setVehicule(string $vehicule): self
    {
        $this->vehicule = $vehicule;

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
    *@return Collection|Order[]
    */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrders(Order $order): self
    {
        if(!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setDriver($this);
        }

        return $this; 
    }

    public function removeorders(Order $order) : self
    {
        if ($this->orders->removeElement(($order))) {

            if($order->getDriver() === $this) {
                $order->setDriver(null);
            }
        }
        return $this;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setDriver($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getDriver() === $this) {
                $order->setDriver(null);
            }
        }

        return $this;
    }
}
