<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\LengthClassProvider;

use App\Domain\Common\Domain\Enum\ConfigEnum;
use App\Domain\Common\Application\Config\ConfigManager;
use App\Domain\Common\Domain\Entity\Base\Front\LengthClass as LengthClassFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\LengthClassProvider\Repository\Front\LengthClassRepository as LengthClassFrontRepository;

class Provider
{
    private ConfigManager $configManager;

    private LengthClassFrontRepository $lengthClassFrontRepository;

    /**
     * @param ConfigManager $configManager
     * @param LengthClassFrontRepository $lengthClassFrontRepository
     */
    public function __construct(
        ConfigManager $configManager,
        LengthClassFrontRepository $lengthClassFrontRepository
    )
    {
        $this->configManager = $configManager;
        $this->lengthClassFrontRepository = $lengthClassFrontRepository;
    }

    /**
     * @return LengthClassFront
     */
    public function getDefaultLengthClassFront(): LengthClassFront
    {
        $id = $this->configManager->getInt(ConfigEnum::FRONT_DEFAULT_LENGTH_CLASS_ID);

        /** @psalm-var LengthClassFront */
        return $this->lengthClassFrontRepository->findOne($id);
    }
}