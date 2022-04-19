<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\AttributeGroupProvider;

use App\Domain\Common\Domain\Enum\ConfigEnum;
use App\Domain\Common\Application\Config\ConfigManager;
use App\Domain\Common\Domain\Entity\Base\Front\AttributeGroup as AttributeGroupFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\AttributeGroupProvider\Repository\Front\AttributeGroupRepository as AttributeGroupFrontRepository;

class Provider
{
    private ConfigManager $configManager;

    private AttributeGroupFrontRepository $attributeGroupFrontRepository;

    /**
     * @param ConfigManager $configManager
     * @param AttributeGroupFrontRepository $attributeGroupFrontRepository
     */
    public function __construct(
        ConfigManager $configManager,
        AttributeGroupFrontRepository $attributeGroupFrontRepository
    )
    {
        $this->configManager = $configManager;
        $this->attributeGroupFrontRepository = $attributeGroupFrontRepository;
    }

    /**
     * @return AttributeGroupFront
     */
    public function getDefaultAttributeGroupFront(): AttributeGroupFront
    {
        $id = $this->configManager->getInt(ConfigEnum::FRONT_DEFAULT_ATTRIBUTE_GROUP_ID);

        /** @psalm-var AttributeGroupFront */
        return $this->attributeGroupFrontRepository->findOne($id);
    }
}