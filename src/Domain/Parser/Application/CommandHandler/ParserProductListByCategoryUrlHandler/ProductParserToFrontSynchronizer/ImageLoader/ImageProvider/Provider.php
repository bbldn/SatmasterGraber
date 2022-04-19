<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ImageLoader\ImageProvider;

use App\Domain\Common\Domain\Enum\ConfigEnum;
use App\Domain\Common\Application\Config\ConfigManager;

class Provider
{
    private ConfigManager $configManager;

    /**
     * @param ConfigManager $configManager
     */
    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->configManager->getString(ConfigEnum::PRODUCT_IMAGE_URL);
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->configManager->getString(ConfigEnum::PRODUCT_IMAGE_PATH);
    }
}