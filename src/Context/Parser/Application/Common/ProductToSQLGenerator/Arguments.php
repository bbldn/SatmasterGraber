<?php

namespace App\Context\Parser\Application\Common\ProductToSQLGenerator;

use App\Context\Parser\Domain\DTO\Product;

class Arguments
{
    private ?int $categoryId = null;

    private ?string $imagePath = null;

    private Product $product;

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param int|null $categoryId
     * @return Arguments
     */
    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    /**
     * @param string|null $imagePath
     * @return Arguments
     */
    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return Arguments
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}