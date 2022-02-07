<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_collection"})
     * @Groups({"get_address"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_pharmacist"})
     * @Groups({"get_address"})
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     * @Assert\NotBlank
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"get_pharmacist"})
     * @Groups({"get_address"})
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Assert\NotBlank
     * @Groups({"get_pharmacists"})
     *
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"get_pharmacist"})
     * @Groups({"get_address"})
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Assert\NotBlank
     * @Groups({"get_pharmacists"})
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="address",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_address"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Dispensary::class, mappedBy="address", cascade={"persist", "remove"})
     * @Groups({"get_collection"})
     * @Groups({"get_address"})
     * @Groups({"get_order"})
     */
    private $dispensary;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Groups({"get_collection"})
     * @Groups({"get_address"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     */
    private $osmId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_collection"})
     * @Groups({"get_address"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_collection"})
     * @Groups({"get_address"})
     * @Groups({"get_order"})
     * @Groups({"get_pharmacists"})
     */
    private $lon;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDispensary(): ?Dispensary
    {
        return $this->dispensary;
    }

    public function setDispensary(Dispensary $dispensary): self
    {
        // set the owning side of the relation if necessary
        if ($dispensary->getAddress() !== $this) {
            $dispensary->setAddress($this);
        }

        $this->dispensary = $dispensary;

        return $this;
    }

    public function getOsmId(): ?int
    {
        return $this->osmId;
    }

    public function setOsmId(?int $osmId): self
    {
        $this->osmId = $osmId;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?string
    {
        return $this->lon;
    }

    public function setLon(?string $lon): self
    {
        $this->lon = $lon;

        return $this;
    }
}
