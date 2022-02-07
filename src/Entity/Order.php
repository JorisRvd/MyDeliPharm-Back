<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;


/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @Vich\Uploadable
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_driver"})
     * @Groups({"get_patient"})
     * 
     */
    private $id;

    /**
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_driver"})
     * @Groups({"get_patient"})
     * 
     */
    private $prescription;
    
    /**
     *  @ORM\Column(type="string", length=255, nullable=true)
     *  @Groups({"get_collection"})
     *  @Groups({"get_order"})
     *  @Groups({"get_driver"})
     *  @Groups({"get_patient"})
     */
    private $prescriptionImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_patient"})
     */
    private $location;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Assert\NotBlank
     * @Groups({"get_driver"})
     */
    private $safetyCode;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"get_collection"})
     * @Groups({"get_order"})
     * @Groups({"get_patient"})
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="orders", cascade={"persist", "remove"})
     * @Groups({"get_order"})
     * 
     * 
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class, inversedBy="orders", cascade={"persist", "remove"})
     * @Groups({"get_order"})
     */
    private $driver;

    /**
     * @ORM\ManyToOne(targetEntity=Pharmacist::class, inversedBy="orders", cascade={"persist", "remove"})
     * @Groups({"get_order"})
     */
    private $pharmacist;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrescription()
    {
        return $this->prescription;
    }
    /**
     * Undocumented function
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     * 
     */
    public function setPrescription(?File $prescription = null)
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

    public function getPrescriptionImage(): ?string
    {
        return $this->prescriptionImage;
    }

    public function setPrescriptionImage(?string $prescriptionImage): self
    {
        $this->prescriptionImage = $prescriptionImage;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }
}





