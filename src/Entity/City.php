<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
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
     * @ORM\Column(type="string", length=100)
     */
    private $change_name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $alias;

    /**
     * @ORM\OneToMany(targetEntity=CityPages::class, mappedBy="city")
     */
    private $cityPages;

    public function __construct()
    {
        $this->cityPages = new ArrayCollection();
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

    public function getChangeName(): ?string
    {
        return $this->change_name;
    }

    public function setChangeName(string $change_name): self
    {
        $this->change_name = $change_name;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return Collection|CityPages[]
     */
    public function getCityPages(): Collection
    {
        return $this->cityPages;
    }

    public function addCityPage(CityPages $cityPage): self
    {
        if (!$this->cityPages->contains($cityPage)) {
            $this->cityPages[] = $cityPage;
            $cityPage->setCity($this);
        }

        return $this;
    }

    public function removeCityPage(CityPages $cityPage): self
    {
        if ($this->cityPages->removeElement($cityPage)) {
            // set the owning side to null (unless already changed)
            if ($cityPage->getCity() === $this) {
                $cityPage->setCity(null);
            }
        }

        return $this;
    }
}
