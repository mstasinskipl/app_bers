<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BeerRepository")
 */
class Beer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brewer", inversedBy="beers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brewer;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $country;

    /**
     * @ORM\Column(type="float")
     */
    private $price_per_litre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img_url;

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

    public function getBrewer(): ?Brewer
    {
        return $this->brewer;
    }

    public function setBrewer(?Brewer $brewer): self
    {
        $this->brewer = $brewer;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPricePerLitre(): ?float
    {
        return $this->price_per_litre;
    }

    public function setPricePerLitre(float $price_per_litre): self
    {
        $this->price_per_litre = $price_per_litre;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->img_url;
    }

    public function setImgUrl(string $img_url): self
    {
        $this->img_url = $img_url;

        return $this;
    }
}
