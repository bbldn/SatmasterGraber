<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\LayoutProvider;

use App\Domain\Common\Domain\Enum\ConfigEnum;
use App\Domain\Common\Application\Config\ConfigManager;
use App\Domain\Common\Domain\Entity\Base\Front\Layout as LayoutFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\LayoutProvider\Repository\Front\LayoutRepository as LayoutFrontRepository;

class Provider
{
    private ConfigManager $configManager;

    private LayoutFrontRepository $layoutFrontRepository;

    /**
     * @param ConfigManager $configManager
     * @param LayoutFrontRepository $layoutFrontRepository
     */
    public function __construct(
        ConfigManager $configManager,
        LayoutFrontRepository $layoutFrontRepository
    )
    {
        $this->configManager = $configManager;
        $this->layoutFrontRepository = $layoutFrontRepository;
    }

    /**
     * @return LayoutFront
     */
    public function getProductLayoutFront(): LayoutFront
    {
        $id = $this->configManager->getInt(ConfigEnum::FRONT_DEFAULT_PRODUCT_LAYOUT_ID);

        /** @psalm-var LayoutFront */
        return $this->layoutFrontRepository->findOne($id);
    }
}