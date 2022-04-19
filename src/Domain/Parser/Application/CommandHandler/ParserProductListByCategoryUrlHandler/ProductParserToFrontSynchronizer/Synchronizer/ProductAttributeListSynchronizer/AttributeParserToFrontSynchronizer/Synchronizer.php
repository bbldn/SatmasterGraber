<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer;

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Domain\Parser\Domain\DTO\Attribute as AttributeParser;
use App\Domain\Common\Domain\Entity\Base\Front\Attribute as AttributeFront;
use App\Domain\Common\Application\Provider\AttributeProvider\Provider as AttributeProvider;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\Synchronizer\AttributeSynchronizer\Synchronizer as AttributeSynchronizer;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\Synchronizer\AttributeDescriptionListSynchronizer\Synchronizer as AttributeDescriptionListSynchronizer;

class Synchronizer
{
    private EntityManager $entityManager;

    private AttributeProvider $attributeProvider;

    private AttributeSynchronizer $attributeSynchronizer;

    private AttributeDescriptionListSynchronizer $attributeDescriptionListSynchronizer;

    /**
     * @param EntityManager $entityManager
     * @param AttributeProvider $attributeProvider
     * @param AttributeSynchronizer $attributeSynchronizer
     * @param AttributeDescriptionListSynchronizer $attributeDescriptionListSynchronizer
     */
    public function __construct(
        EntityManager $entityManager,
        AttributeProvider $attributeProvider,
        AttributeSynchronizer $attributeSynchronizer,
        AttributeDescriptionListSynchronizer $attributeDescriptionListSynchronizer
    )
    {
        $this->entityManager = $entityManager;
        $this->attributeProvider = $attributeProvider;
        $this->attributeSynchronizer = $attributeSynchronizer;
        $this->attributeDescriptionListSynchronizer = $attributeDescriptionListSynchronizer;
    }

    /**
     * @param AttributeParser $attributeParser
     * @return AttributeFront
     */
    public function synchronize(AttributeParser $attributeParser): AttributeFront
    {
        $attributeFront = $this->attributeProvider->getAttributeFrontByAttributeParser($attributeParser);
        if (null === $attributeFront) {
            $attributeFront = new AttributeFront();
            $this->entityManager->persist($attributeFront);
        }

        $this->attributeSynchronizer->synchronize($attributeFront);
        $this->attributeDescriptionListSynchronizer->synchronize($attributeFront, $attributeParser);

        return $attributeFront;
    }
}