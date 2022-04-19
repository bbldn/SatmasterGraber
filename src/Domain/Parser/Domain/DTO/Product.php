<?php

namespace App\Domain\Parser\Domain\DTO;

class Product
{
    private ?int $id = null;

    private ?float $price = null;

    private ?string $name = null;

    private ?string $image = null;

    private ?string $description = null;

    /**
     * @var string[]|null
     *
     * @psalm-param list<string>|null
     */
    private ?array $imageList = null;

    /**
     * @var Attribute[]|null
     *
     * @psalm-var list<Attribute>|null
     */
    private ?array $attributeList = null;

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
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return Product
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

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
    public function getImageList(): ?array
    {
        return $this->imageList;
    }

    /**
     * @param string[]|null $imageList
     * @return Product
     *
     * @psalm-param list<string>|null $imageList
     */
    public function setImageList(?array $imageList): self
    {
        $this->imageList = $imageList;

        return $this;
    }

    /**
     * @return Attribute[]|null
     *
     * @psalm-return list<Attribute>|null
     */
    public function getAttributeList(): ?array
    {
        return $this->attributeList;
    }

    /**
     * @param Attribute[]|null $attributeList
     * @return Product
     *
     * @psalm-param list<Attribute>|null $attributeList
     */
    public function setAttributeList(?array $attributeList): Product
    {
        $this->attributeList = $attributeList;

        return $this;
    }
}