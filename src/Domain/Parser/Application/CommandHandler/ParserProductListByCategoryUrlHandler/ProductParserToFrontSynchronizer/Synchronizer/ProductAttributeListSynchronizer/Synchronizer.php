<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer;

use App\Domain\Parser\Domain\DTO\Product as ProductParser;
use App\Domain\Common\Domain\Entity\Base\Front\Product as ProductFront;
use App\Domain\Common\Domain\Entity\Base\Front\Language as LanguageFront;
use App\Domain\Common\Domain\Entity\Base\Front\Attribute as AttributeFront;
use App\Domain\Common\Domain\Entity\Base\Front\ProductAttribute as ProductAttributeFront;
use App\Domain\Common\Application\Provider\LanguageProvider\Provider as LanguageProvider;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\Synchronizer as AttributeParserToFrontSynchronizer;

class Synchronizer
{
    private LanguageProvider $languageProvider;

    private AttributeParserToFrontSynchronizer $attributeParserToFrontSynchronizer;

    /**
     * @param LanguageProvider $languageProvider
     * @param AttributeParserToFrontSynchronizer $attributeParserToFrontSynchronizer
     */
    public function __construct(
        LanguageProvider $languageProvider,
        AttributeParserToFrontSynchronizer $attributeParserToFrontSynchronizer
    )
    {
        $this->languageProvider = $languageProvider;
        $this->attributeParserToFrontSynchronizer = $attributeParserToFrontSynchronizer;
    }

    /**
     * @param ProductFront $productFront
     * @param ProductParser $productParser
     * @return void
     */
    public function synchronize(ProductFront $productFront, ProductParser $productParser): void
    {
        $defaultLanguageFront = $this->languageProvider->getDefaultLanguageFront();

        $productAttributeFrontMapByAttributeFrontId = [];
        foreach ($productFront->getProductAttributes() as $index => $productAttributeFront) {
            /** @var LanguageFront $languageFront */
            $languageFront = $productAttributeFront->getLanguage();
            if ($languageFront->getId() !== $defaultLanguageFront->getId()) {
                $productFront->getProductAttributes()->remove($index);
                continue;
            }

            /** @var AttributeFront $attributeFront */
            $attributeFront = $productAttributeFront->getAttribute();

            /** @var int $attributeFrontId */
            $attributeFrontId = $attributeFront->getId();
            $productAttributeFrontMapByAttributeFrontId[$attributeFrontId] = $productAttributeFront;
        }

        foreach ($productParser->getAttributes() ?? [] as $attributeParser) {
            $attributeFront = $this->attributeParserToFrontSynchronizer->synchronize($attributeParser);

            /** @var int $attributeFrontId */
            $attributeFrontId = $attributeFront->getId();

            $productAttributeFront = $productAttributeFrontMapByAttributeFrontId[$attributeFrontId] ?? new ProductAttributeFront();
            $productAttributeFront->setProduct($productFront);
            $productAttributeFront->setAttribute($attributeFront);
            $productAttributeFront->setLanguage($defaultLanguageFront);
            $productAttributeFront->setText($attributeParser->getValue());
            $productFront->getProductAttributes()->add($productAttributeFront);
        }
    }
}