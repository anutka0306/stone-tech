<?php

namespace App\Entity;

use App\Repository\StoneProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StoneProductRepository::class)
 * @ORM\EntityListeners({"App\Doctrine\GenerateUrlByNewStoneProduct"})
 */
class StoneProduct
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $small_img;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $big_img;

    /**
     * @ORM\ManyToOne(targetEntity=StoneCatalog::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity=Color::class, inversedBy="stones")
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="stones")
     */
    private $country;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSmallImg(): ?string
    {
        return $this->small_img;
    }

    public function setSmallImg(string $small_img): self
    {
        $this->small_img = $small_img;

        return $this;
    }

    public function getBigImg(): ?string
    {
        return $this->big_img;
    }

    public function setBigImg(string $big_img): self
    {
        $this->big_img = $big_img;

        return $this;
    }

    public function getParent(): ?StoneCatalog
    {
        return $this->parent;
    }

    public function setParent(?StoneCatalog $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}
