<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer;

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Domain\Parser\Domain\DTO\Product as ProductGraber;
use App\Domain\Common\Domain\Entity\Base\Front\Product as ProductFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ProductProvider\Provider as ProductProvider;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\Synchronizer as ProductSynchronizer;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductToLayoutSynchronizer\Synchronizer as ProductToLayoutSynchronizer;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductShopListSynchronizer\Synchronizer as ProductShopListSynchronizer;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductImageListSynchronizer\Synchronizer as ProductImageListSynchronizer;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\Synchronizer as ProductAttributeListSynchronizer;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductDescriptionListSynchronizer\Synchronizer as ProductDescriptionListSynchronizer;

class Synchronizer
{
    private ProductProvider $productProvider;

    private EntityManager $entityManagerFront;

    private ProductSynchronizer $productSynchronizer;

    private ProductToLayoutSynchronizer $productToLayoutSynchronizer;

    private ProductShopListSynchronizer $productShopListSynchronizer;

    private ProductImageListSynchronizer $productImageListSynchronizer;

    private ProductAttributeListSynchronizer $productAttributeListSynchronizer;

    private ProductDescriptionListSynchronizer $productDescriptionListSynchronizer;

    /**
     * @param ProductProvider $productProvider
     * @param EntityManager $entityManagerFront
     * @param ProductSynchronizer $productSynchronizer
     * @param ProductToLayoutSynchronizer $productToLayoutSynchronizer
     * @param ProductShopListSynchronizer $productShopListSynchronizer
     * @param ProductImageListSynchronizer $productImageListSynchronizer
     * @param ProductAttributeListSynchronizer $productAttributeListSynchronizer
     * @param ProductDescriptionListSynchronizer $productDescriptionListSynchronizer
     */
    public function __construct(
        ProductProvider $productProvider,
        EntityManager $entityManagerFront,
        ProductSynchronizer $productSynchronizer,
        ProductToLayoutSynchronizer $productToLayoutSynchronizer,
        ProductShopListSynchronizer $productShopListSynchronizer,
        ProductImageListSynchronizer $productImageListSynchronizer,
        ProductAttributeListSynchronizer $productAttributeListSynchronizer,
        ProductDescriptionListSynchronizer $productDescriptionListSynchronizer
    )
    {
        $this->productProvider = $productProvider;
        $this->entityManagerFront = $entityManagerFront;
        $this->productSynchronizer = $productSynchronizer;
        $this->productToLayoutSynchronizer = $productToLayoutSynchronizer;
        $this->productShopListSynchronizer = $productShopListSynchronizer;
        $this->productImageListSynchronizer = $productImageListSynchronizer;
        $this->productAttributeListSynchronizer = $productAttributeListSynchronizer;
        $this->productDescriptionListSynchronizer = $productDescriptionListSynchronizer;
    }

    /**
     * @param ProductGraber $productGraber
     * @return ProductFront
     */
    private function getProductFrontByProductParser(ProductGraber $productGraber): ProductFront
    {
        $productFront = $this->productProvider->getProductFrontByProductParser($productGraber);
        if (null !== $productFront) {
            return $productFront;
        }

        $productFront = new ProductFront();
        $this->entityManagerFront->persist($productFront);

        return $productFront;
    }

    /**
     * @param ProductGraber $productGraber
     * @return ProductFront
     */
    public function synchronize(ProductGraber $productGraber): ProductFront
    {
        $productFront = $this->getProductFrontByProductParser($productGraber);

        $this->productToLayoutSynchronizer->synchronize($productFront);
        $this->productShopListSynchronizer->synchronize($productFront);
        $this->productSynchronizer->synchronize($productFront, $productGraber);
        $this->productImageListSynchronizer->synchronize($productFront, $productGraber);
        $this->productAttributeListSynchronizer->synchronize($productFront, $productGraber);
        $this->productDescriptionListSynchronizer->synchronize($productFront, $productGraber);

        return $productFront;
    }
}