<?php

namespace App\Entity;

use App\Repository\CityPagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityPagesRepository::class)
 */
class CityPages
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
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity=Content::class, inversedBy="cityPages")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="cityPages")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $seo_title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $seo_description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $seo_text;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $seo_text_hidden;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $seo_text_img;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getParent(): ?Content
    {
        return $this->parent;
    }

    public function setParent(?Content $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seo_title;
    }

    public function setSeoTitle(?string $seo_title): self
    {
        $this->seo_title = $seo_title;

        return $this;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seo_description;
    }

    public function setSeoDescription(?string $seo_description): self
    {
        $this->seo_description = $seo_description;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getSeoText(): ?string
    {
        return $this->seo_text;
    }

    public function setSeoText(?string $seo_text): self
    {
        $this->seo_text = $seo_text;

        return $this;
    }

    public function getSeoTextHidden(): ?string
    {
        return $this->seo_text_hidden;
    }

    public function setSeoTextHidden(?string $seo_text_hidden): self
    {
        $this->seo_text_hidden = $seo_text_hidden;

        return $this;
    }

    public function getSeoTextImg(): ?string
    {
        return $this->seo_text_img;
    }

    public function setSeoTextImg(?string $seo_text_img): self
    {
        $this->seo_text_img = $seo_text_img;

        return $this;
    }
}
