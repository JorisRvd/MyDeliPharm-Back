<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vitalCardNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mutuelleNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $other;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    private $vitalCardFile;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    private $mutuelleFile;

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
}
