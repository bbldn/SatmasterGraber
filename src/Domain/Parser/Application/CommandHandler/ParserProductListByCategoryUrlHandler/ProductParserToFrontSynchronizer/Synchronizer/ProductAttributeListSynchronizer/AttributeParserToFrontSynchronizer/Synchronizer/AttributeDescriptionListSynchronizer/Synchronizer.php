<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\Synchronizer\AttributeDescriptionListSynchronizer;

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Domain\Parser\Domain\DTO\Attribute as AttributeParser;
use App\Domain\Common\Domain\Entity\Base\Front\Language as LanguageFront;
use App\Domain\Common\Domain\Entity\Base\Front\Attribute as AttributeFront;
use App\Domain\Common\Application\Provider\LanguageProvider\Provider as LanguageProvider;
use App\Domain\Common\Domain\Entity\Base\Front\AttributeDescription as AttributeDescriptionFront;

class Synchronizer
{
    private EntityManager $entityManagerFront;

    private LanguageProvider $languageProvider;

    /**
     * @param EntityManager $entityManagerFront
     * @param LanguageProvider $languageProvider
     */
    public function __construct(
        EntityManager $entityManagerFront,
        LanguageProvider $languageProvider
    )
    {
        $this->languageProvider = $languageProvider;
        $this->entityManagerFront = $entityManagerFront;
    }

    /**
     * @param LanguageFront $languageFront
     * @param AttributeFront $attributeFront
     * @param AttributeParser $attributeParser
     * @param AttributeDescriptionFront $attributeDescriptionFront
     * @return void
     */
    private function fillAttributeDescriptionFront(
        LanguageFront $languageFront,
        AttributeFront $attributeFront,
        AttributeParser $attributeParser,
        AttributeDescriptionFront $attributeDescriptionFront
    ): void
    {
        $attributeDescriptionFront->setLanguage($languageFront);
        $attributeDescriptionFront->setAttribute($attributeFront);
        $attributeDescriptionFront->setName($attributeParser->getName());
    }

    /**
     * @param AttributeFront $attributeFront
     * @param AttributeParser $attributeParser
     * @return void
     */
    public function synchronize(AttributeFront $attributeFront, AttributeParser $attributeParser): void
    {
        $needFor = true;

        $defaultLanguageFront = $this->languageProvider->getDefaultLanguageFront();
        foreach ($attributeFront->getDescriptions() as $index => $attributeDescriptionFront) {
            /** @var LanguageFront $languageFront */
            $languageFront = $attributeDescriptionFront->getLanguage();
            if ($languageFront->getId() !== $defaultLanguageFront->getId()) {
                $attributeFront->getDescriptions()->remove($index);
                continue;
            }

            $this->fillAttributeDescriptionFront(
                $defaultLanguageFront,
                $attributeFront,
                $attributeParser,
                $attributeDescriptionFront
            );
            $needFor = false;
        }

        if (false === $needFor) {
            return;
        }

        $attributeDescriptionFront = new AttributeDescriptionFront();
        $attributeFront->getDescriptions()->add($attributeDescriptionFront);
        $this->fillAttributeDescriptionFront(
            $defaultLanguageFront,
            $attributeFront,
            $attributeParser,
            $attributeDescriptionFront
        );

        $this->entityManagerFront->persist($attributeFront);
    }
}