<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductToLayoutSynchronizer;

use App\Domain\Common\Domain\Entity\Base\Front\ProductToLayout;
use App\Domain\Common\Domain\Entity\Base\Front\Shop as ShopFront;
use App\Domain\Common\Domain\Entity\Base\Front\Product as ProductFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ShopProvider\Provider as ShopProvider;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\LayoutProvider\Provider as LayoutProvider;

class Synchronizer
{
    private ShopProvider $shopProvider;

    private LayoutProvider $layoutProvider;

    /**
     * @param ShopProvider $shopProvider
     * @param LayoutProvider $layoutProvider
     */
    public function __construct(
        ShopProvider $shopProvider,
        LayoutProvider $layoutProvider
    )
    {
        $this->shopProvider = $shopProvider;
        $this->layoutProvider = $layoutProvider;
    }

    /**
     * @param ProductFront $productFront
     * @return void
     */
    public function synchronize(ProductFront $productFront): void
    {
        $defaultShopFront = $this->shopProvider->getDefaultShopFront();

        $mainProductToLayoutFront = null;
        foreach ($productFront->getProductToLayouts() as $index => $productToLayoutFront) {
            /** @var ShopFront $shopFront */
            $shopFront = $productToLayoutFront->getShop();

            if ($shopFront->getId() !== $defaultShopFront->getId()) {
                $productFront->getProductToLayouts()->remove($index);
                continue;
            }

            $mainProductToLayoutFront = $productToLayoutFront;
            break;
        }

        if (null !== $mainProductToLayoutFront) {
            return;
        }

        $mainLayoutFront = $this->layoutProvider->getProductLayoutFront();

        $mainProductToLayoutFront = new ProductToLayout();
        $mainProductToLayoutFront->setProduct($productFront);
        $mainProductToLayoutFront->setShop($defaultShopFront);
        $mainProductToLayoutFront->setLayout($mainLayoutFront);
        $productFront->getProductToLayouts()->add($mainProductToLayoutFront);
    }
}