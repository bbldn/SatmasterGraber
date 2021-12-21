<?php

namespace App\Domain\Parser\Application\Common\ProductToSQLGenerator;

use App\Domain\Parser\Domain\DTO\Product;

class ArgumentList
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
     * @return ArgumentList
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
     * @return ArgumentList
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
     * @return ArgumentList
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}