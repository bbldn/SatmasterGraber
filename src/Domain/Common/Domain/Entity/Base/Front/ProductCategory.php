<?php

namespace App\Domain\Common\Domain\Entity\Base\Front;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Common\Infrastructure\Repository\Base\Front\ProductCategoryRepository;

/**
 * @ORM\Table(name="`oc_product_to_category`")
 * @ORM\Entity(repositoryClass=ProductCategoryRepository::class)
 */
class ProductCategory
{
    /**
     * @ORM\Id()
     * @ORM\JoinColumn(name="`product_id`", referencedColumnName="`product_id`")
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productToCategories")
     */
    private ?Product $product = null;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="descriptions")
     * @ORM\JoinColumn(name="`category_id`", referencedColumnName="`category_id`")
     */
    private ?Category $category = null;

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return ProductCategory
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return ProductCategory
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}