<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductFrontCategorySetter;

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Domain\Common\Domain\Entity\Base\Front\ProductCategory;
use App\Domain\Common\Domain\Entity\Base\Front\Product as ProductFront;
use App\Domain\Common\Domain\Entity\Base\Front\Category as CategoryFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductFrontCategorySetter\Repository\Front\CategoryRepository as CategoryFrontRepository;

class Setter
{
    private EntityManager $entityManagerFront;

    private CategoryFrontRepository $categoryFrontRepository;

    /**
     * @param EntityManager $entityManagerFront
     * @param CategoryFrontRepository $categoryFrontRepository
     */
    public function __construct(
        EntityManager $entityManagerFront,
        CategoryFrontRepository $categoryFrontRepository
    )
    {
        $this->entityManagerFront = $entityManagerFront;
        $this->categoryFrontRepository = $categoryFrontRepository;
    }

    /**
     * @param ProductFront $productFront
     * @param int|null $categoryFrontId
     * @return void
     */
    public function set(ProductFront $productFront, ?int $categoryFrontId): void
    {
        if (null === $categoryFrontId) {
            $productFront->getProductToCategories()->clear();
            $this->entityManagerFront->persist($productFront);

            return;
        }

        $needAdd = true;
        foreach ($productFront->getProductToCategories() as $index => $productToCategoryFront) {
            /** @var CategoryFront $category */
            $category = $productToCategoryFront->getCategory();
            if ($category->getId() !== $categoryFrontId) {
                $productFront->getProductToCategories()->remove($index);
                continue;
            }

            $needAdd = false;
        }

        if (true === $needAdd) {
            $categoryFront = $this->categoryFrontRepository->findOne($categoryFrontId);

            $productToCategoryFront = new ProductCategory();
            $productToCategoryFront->setProduct($productFront);
            $productToCategoryFront->setCategory($categoryFront);
            $productFront->getProductToCategories()->add($productToCategoryFront);
        }

        $this->entityManagerFront->persist($productFront);
    }
}