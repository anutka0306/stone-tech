<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ColorRepository::class)
 */
class Color
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;
    

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="color")
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color_plural;

    /**
     * @ORM\OneToMany(targetEntity=StoneProduct::class, mappedBy="color")
     */
    private $stones;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->stones = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Products[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setColor($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getColor() === $this) {
                $product->setColor(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getColorPlural(): ?string
    {
        return $this->color_plural;
    }

    public function setColorPlural(string $color_plural): self
    {
        $this->color_plural = $color_plural;

        return $this;
    }

    /**
     * @return Collection|StoneProduct[]
     */
    public function getStones(): Collection
    {
        return $this->stones;
    }

    public function addStone(StoneProduct $stone): self
    {
        if (!$this->stones->contains($stone)) {
            $this->stones[] = $stone;
            $stone->setColor($this);
        }

        return $this;
    }

    public function removeStone(StoneProduct $stone): self
    {
        if ($this->stones->removeElement($stone)) {
            // set the owning side to null (unless already changed)
            if ($stone->getColor() === $this) {
                $stone->setColor(null);
            }
        }

        return $this;
    }
}
