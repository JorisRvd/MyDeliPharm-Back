<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 */
class Driver
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $vehicule;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $profilPic;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="driver", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, mappedBy="driver", cascade={"persist", "remove"})
     */
    private $orders;

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

    public function getProfilPic(): ?string
    {
        return $this->profilPic;
    }

    public function setProfilPic(string $profilPic): self
    {
        $this->profilPic = $profilPic;

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
            $this->orders->setDriver(null);
        }

        // set the owning side of the relation if necessary
        if ($orders !== null && $orders->getDriver() !== $this) {
            $orders->setDriver($this);
        }

        $this->orders = $orders;

        return $this;
    }
}
