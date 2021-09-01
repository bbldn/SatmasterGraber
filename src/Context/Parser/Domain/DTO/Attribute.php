<?php

namespace App\Context\Parser\Domain\DTO;

class Attribute
{
    private ?string $name = null;

    private ?string $value = null;

    private ?Product $product = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Attribute
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return Attribute
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return Attribute
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}