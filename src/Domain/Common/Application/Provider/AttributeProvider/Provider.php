<?php

namespace App\Domain\Common\Application\Provider\AttributeProvider;

use App\Domain\Parser\Domain\DTO\Attribute as AttributeParser;
use App\Domain\Common\Domain\Entity\Base\Front\Attribute as AttributeFront;
use App\Domain\Common\Application\Provider\AttributeProvider\Repository\Front\AttributeDescriptionRepository as AttributeDescriptionFrontRepository;

class Provider
{
    private AttributeDescriptionFrontRepository $attributeDescriptionFrontRepository;

    /**
     * @param AttributeDescriptionFrontRepository $attributeDescriptionFrontRepository
     */
    public function __construct(AttributeDescriptionFrontRepository $attributeDescriptionFrontRepository)
    {
        $this->attributeDescriptionFrontRepository = $attributeDescriptionFrontRepository;
    }

    /**
     * @param AttributeParser $attributeParser
     * @return AttributeFront|null
     */
    public function getAttributeFrontByAttributeParser(AttributeParser $attributeParser): ?AttributeFront
    {
        $name = (string)$attributeParser->getName();
        $attributeDescriptionFront = $this->attributeDescriptionFrontRepository->findOneByName($name);
        if (null !== $attributeDescriptionFront) {
            return $attributeDescriptionFront->getAttribute();
        }

        return null;
    }
}