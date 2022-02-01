<?php

namespace App\Entity;

use App\Repository\DispensaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DispensaryRepository::class)
 */
class Dispensary
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     * @Groups({"get_pharmacist"})
     * 
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_pharmacist"})
     */
    private $other;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"get_pharmacist"})
     * 
     */
    private $openingHours;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, inversedBy="dispensary", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get_pharmacist"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="dispensary", orphanRemoval=true)
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity=Pharmacist::class, mappedBy="dispensary", orphanRemoval=true)
     */
    private $pharmacist;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->pharmacist = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOther(): ?string
    {
        return $this->other;
    }

    public function setOther(?string $other): self
    {
        $this->other = $other;

        return $this;
    }

    public function getOpeningHours(): ?string
    {
        return $this->openingHours;
    }

    public function setOpeningHours(string $openingHours): self
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setDispensary($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getDispensary() === $this) {
                $patient->setDispensary(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Pharmacist[]
     */
    public function getPharmacist(): Collection
    {
        return $this->pharmacist;
    }

    public function addPharmacist(Pharmacist $pharmacist): self
    {
        if (!$this->pharmacist->contains($pharmacist)) {
            $this->pharmacist[] = $pharmacist;
            $pharmacist->setDispensary($this);
        }

        return $this;
    }

    public function removePharmacist(Pharmacist $pharmacist): self
    {
        if ($this->pharmacist->removeElement($pharmacist)) {
            // set the owning side to null (unless already changed)
            if ($pharmacist->getDispensary() === $this) {
                $pharmacist->setDispensary(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
