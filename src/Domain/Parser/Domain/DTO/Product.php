<?php

namespace App\Domain\Parser\Domain\DTO;

class Product
{
    private ?int $id = null;

    private ?float $price = null;

    private ?string $name = null;

    private ?string $description = null;

    /**
     * @var string[]|null
     *
     * @psalm-param list<string>|null
     */
    private ?array $images = null;

    /**
     * @var Attribute[]|null
     *
     * @psalm-var list<Attribute>|null
     */
    private ?array $attributes = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Product
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return Product
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Product
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Product
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string[]|null
     *
     * @psalm-return list<string>|null
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @param string[]|null $images
     * @return Product
     *
     * @psalm-param list<string>|null $images
     */
    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return Attribute[]|null
     *
     * @psalm-return list<Attribute>|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * @param Attribute[]|null $attributes
     * @return Product
     *
     * @psalm-param list<Attribute>|null $attributes
     */
    public function setAttributes(?array $attributes): Product
    {
        $this->attributes = $attributes;

        return $this;
    }
}