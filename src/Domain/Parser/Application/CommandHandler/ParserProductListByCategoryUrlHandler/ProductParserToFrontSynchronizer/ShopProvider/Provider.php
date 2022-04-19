<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ShopProvider;

use App\Domain\Common\Domain\Enum\ConfigEnum;
use App\Domain\Common\Application\Config\ConfigManager;
use App\Domain\Common\Domain\Entity\Base\Front\Shop as ShopFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ShopProvider\Repository\Front\ShopRepository as ShopFrontRepository;

class Provider
{
    private ConfigManager $configManager;

    private ShopFrontRepository $shopFrontRepository;

    /**
     * @param ConfigManager $configManager
     * @param ShopFrontRepository $shopFrontRepository
     */
    public function __construct(
        ConfigManager $configManager,
        ShopFrontRepository $shopFrontRepository
    )
    {
        $this->configManager = $configManager;
        $this->shopFrontRepository = $shopFrontRepository;
    }

    /**
     * @return ShopFront
     */
    public function getDefaultShopFront(): ShopFront
    {
        $id = $this->configManager->getInt(ConfigEnum::FRONT_DEFAULT_SHOP_ID);

        /** @psalm-var ShopFront */
        return $this->shopFrontRepository->findOne($id);
    }
}