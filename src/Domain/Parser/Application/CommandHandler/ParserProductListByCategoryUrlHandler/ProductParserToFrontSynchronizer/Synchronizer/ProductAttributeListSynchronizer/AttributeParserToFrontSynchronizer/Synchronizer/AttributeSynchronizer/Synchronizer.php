<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\Synchronizer\AttributeSynchronizer;

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Domain\Common\Domain\Entity\Base\Front\Attribute as AttributeFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\AttributeGroupProvider\Provider as AttributeGroupProvider;

class Synchronizer
{
    private EntityManager $entityManagerFront;

    private AttributeGroupProvider $attributeGroupProvider;

    /**
     * @param EntityManager $entityManagerFront
     * @param AttributeGroupProvider $attributeGroupProvider
     */
    public function __construct(
        EntityManager $entityManagerFront,
        AttributeGroupProvider $attributeGroupProvider
    )
    {
        $this->entityManagerFront = $entityManagerFront;
        $this->attributeGroupProvider = $attributeGroupProvider;
    }

    /**
     * @param AttributeFront $attributeFront
     * @return void
     */
    public function synchronize(AttributeFront $attributeFront): void
    {
        $attributeGroupFront = $this->attributeGroupProvider->getDefaultAttributeGroupFront();

        $attributeFront->setSortOrder(0);
        $attributeFront->setGroup($attributeGroupFront);

        $this->entityManagerFront->persist($attributeFront);
    }
}