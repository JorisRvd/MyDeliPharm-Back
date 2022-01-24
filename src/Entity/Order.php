<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_order"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2048)
     * @Groups({"get_order"})
     * @Assert\Url
     */
    private $prescription;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"get_order"})
     * @Assert\NotBlank
     */
    private $safetyCode;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"get_order"})
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=Patient::class, inversedBy="orders", cascade={"persist", "remove"})
     */
    private $patient;

    /**
     * @ORM\OneToOne(targetEntity=Driver::class, inversedBy="orders", cascade={"persist", "remove"})
     */
    private $driver;

    /**
     * @ORM\OneToOne(targetEntity=Pharmacist::class, inversedBy="orders", cascade={"persist", "remove"})
     */
    private $pharmacist;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrescription(): ?string
    {
        return $this->prescription;
    }

    public function setPrescription(string $prescription): self
    {
        $this->prescription = $prescription;

        return $this;
    }

    public function getSafetyCode(): ?int
    {
        return $this->safetyCode;
    }

    public function setSafetyCode(int $safetyCode): self
    {
        $this->safetyCode = $safetyCode;

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

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getPharmacist(): ?Pharmacist
    {
        return $this->pharmacist;
    }

    public function setPharmacist(?Pharmacist $pharmacist): self
    {
        $this->pharmacist = $pharmacist;

        return $this;
    }
}
