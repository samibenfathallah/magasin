<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */

class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Designation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Description;


    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatAt;

    /**
     * @ORM\Column(type="float")
     */
    private $Qty;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Supplier::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Supplier;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Category;
    public function __construct()
    {
    $this->CreatAt = new \DateTime('now');
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->Designation;
    }

    public function setDesignation(string $Designation): self
    {
        $this->Designation = $Designation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }


    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->CreatAt;
    }

    public function setCreatAt(\DateTimeInterface $CreatAt): self
    {
        $this->CreatAt = $CreatAt;

        return $this;
    }

    public function getQty(): ?float
    {
        return $this->Qty;
    }

    public function setQty(float $Qty): self
    {
        $this->Qty = $Qty;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->Supplier;
    }

    public function setSupplier(?Supplier $Supplier): self
    {
        $this->Supplier = $Supplier;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

        return $this;
    }
}
