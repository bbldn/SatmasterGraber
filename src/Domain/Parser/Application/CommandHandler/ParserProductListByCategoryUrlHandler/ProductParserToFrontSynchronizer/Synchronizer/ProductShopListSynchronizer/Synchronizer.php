<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductShopListSynchronizer;

use App\Domain\Common\Domain\Entity\Base\Front\Product as ProductFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ShopProvider\Provider as ShopProvider;

class Synchronizer
{
    private ShopProvider $shopProvider;

    /**
     * @param ShopProvider $shopProvider
     */
    public function __construct(ShopProvider $shopProvider)
    {
        $this->shopProvider = $shopProvider;
    }

    /**
     * @param ProductFront $productFront
     * @return void
     */
    public function synchronize(ProductFront $productFront): void
    {
        $needAdd = true;
        $defaultShopFront = $this->shopProvider->getDefaultShopFront();
        foreach ($productFront->getShops() as $index => $shopFront) {
            if ($shopFront->getId() !== $defaultShopFront->getId()) {
                $productFront->getShops()->remove($index);
                continue;
            }

            $needAdd = false;
        }

        if (true === $needAdd) {
            $productFront->getShops()->add($defaultShopFront);
        }
    }
}