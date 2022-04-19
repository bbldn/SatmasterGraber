<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\WeightClassProvider;

use App\Domain\Common\Domain\Enum\ConfigEnum;
use App\Domain\Common\Application\Config\ConfigManager;
use App\Domain\Common\Domain\Entity\Base\Front\WeightClass as WeightClassFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\WeightClassProvider\Repository\Front\WeightClassRepository as WeightClassFrontRepository;

class Provider
{
    private ConfigManager $configManager;

    private WeightClassFrontRepository $weightClassFrontRepository;

    /**
     * @param ConfigManager $configManager
     * @param WeightClassFrontRepository $weightClassFrontRepository
     */
    public function __construct(
        ConfigManager $configManager,
        WeightClassFrontRepository $weightClassFrontRepository
    )
    {
        $this->configManager = $configManager;
        $this->weightClassFrontRepository = $weightClassFrontRepository;
    }

    /**
     * @return WeightClassFront
     */
    public function getDefaultWeightClassFront(): ?WeightClassFront
    {
        $id = $this->configManager->getInt(ConfigEnum::FRONT_DEFAULT_WEIGHT_CLASS_ID);

        /** @psalm-var WeightClassFront */
        return $this->weightClassFrontRepository->findOne($id);
    }
}