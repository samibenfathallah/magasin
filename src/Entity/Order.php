<?php

namespace App\Entity;
use App\Entity\Product;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $Product 
     * @ORM\ManyToMany(targetEntity=Product::class)
     */
    private $Product;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CereatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $Qty;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Customer;

   

    public function __construct()
    {
        $this->Product = new ArrayCollection();
        $this->CereatedAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->Product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->Product->contains($product)) {
            $this->Product[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->Product->contains($product)) {
            $this->Product->removeElement($product);
        }

        return $this;
    }

    public function getCereatedAt(): ?\DateTimeInterface
    {
        return $this->CereatedAt;
    }

    public function setCereatedAt(\DateTimeInterface $CereatedAt): self
    {
        $this->CereatedAt = $CereatedAt;

        return $this; 
    }

    public function getQty(): ?int
    {
        return $this->Qty;
    }

    public function setQty(int $Qty): self
    {
        $this->Qty = $Qty;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->Customer;
    }

    public function setCustomer(?User $Customer): self
    {
        $this->Customer = $Customer;

        return $this;
    }

    
}
