<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
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
     * @ORM\OneToMany(targetEntity=StoneProduct::class, mappedBy="country")
     */
    private $stones;

    public function __construct()
    {
        $this->stones = new ArrayCollection();
    }

    public function __toString(){
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
            $stone->setCountry($this);
        }

        return $this;
    }

    public function removeStone(StoneProduct $stone): self
    {
        if ($this->stones->removeElement($stone)) {
            // set the owning side to null (unless already changed)
            if ($stone->getCountry() === $this) {
                $stone->setCountry(null);
            }
        }

        return $this;
    }
}
