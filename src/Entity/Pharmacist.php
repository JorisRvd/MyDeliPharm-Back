<?php

namespace App\Entity;

use App\Repository\PharmacistRepository;
use Doctrine\ORM\Mapping as ORM;
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
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"get_collection"},{"get_pharmacist"})
     * 
     * 
     * 
     */
    private $rppsNumber;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"get_collection"},{"get_pharmacist"})
     * @Assert\NotBlank
     * 
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     * @Groups({"get_pharmacist"})
     * @Assert\Url
     */
    private $profilPic;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="pharmacist", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="pharmacist", cascade={"persist", "remove"})
     * @Groups({"get_pharmacist"})
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=Dispensary::class, inversedBy="pharmacist",cascade={"persist"})
     * @Groups({"get_pharmacist"})
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

    public function getProfilPic(): ?string
    {
        return $this->profilPic;
    }

    public function setProfilPic(?string $profilPic): self
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
            $this->orders->setPharmacist(null);
        }

        // set the owning side of the relation if necessary
        if ($orders !== null && $orders->getPharmacist() !== $this) {
            $orders->setPharmacist($this);
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
