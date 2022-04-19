<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductDescriptionListSynchronizer;

use App\Domain\Parser\Domain\DTO\Product as ProductParser;
use App\Domain\Common\Domain\Entity\Base\Front\Product as ProductFront;
use App\Domain\Common\Domain\Entity\Base\Front\Language as LanguageFront;
use App\Domain\Common\Application\Provider\LanguageProvider\Provider as LanguageProvider;
use App\Domain\Common\Domain\Entity\Base\Front\ProductDescription as ProductDescriptionFront;

class Synchronizer
{
    private LanguageProvider $languageProvider;

    /**
     * @param LanguageProvider $languageProvider
     */
    public function __construct(LanguageProvider $languageProvider)
    {
        $this->languageProvider = $languageProvider;
    }

    /**
     * @param ProductFront $productFront
     * @param ProductParser $productParser
     * @param LanguageFront $languageFront
     * @param ProductDescriptionFront $productDescriptionFront
     * @return void
     */
    public function fillProductDescriptionFront(
        ProductFront $productFront,
        ProductParser $productParser,
        LanguageFront $languageFront,
        ProductDescriptionFront $productDescriptionFront
    ): void
    {
        $name = $productParser->getName();
        $description = $productParser->getDescription();

        $productDescriptionFront->setTag('');
        $productDescriptionFront->setName($name);
        $productDescriptionFront->setMetaTitle('');
        $productDescriptionFront->setMetaKeyword('');
        $productDescriptionFront->setMetaDescription('');
        $productDescriptionFront->setProduct($productFront);
        $productDescriptionFront->setLanguage($languageFront);
        $productDescriptionFront->setDescription((string)$description);
    }

    /**
     * @param ProductFront $productFront
     * @param ProductParser $productParser
     * @return void
     */
    public function synchronize(ProductFront $productFront, ProductParser $productParser): void
    {
        $needAdd = true;
        $defaultLanguageFront = $this->languageProvider->getDefaultLanguageFront();
        foreach ($productFront->getDescriptions() as $index => $productDescriptionFront) {
            $languageFront = $productDescriptionFront->getLanguage();
            if (null !== $languageFront && $languageFront->getId() !== $defaultLanguageFront->getId()) {
                $productFront->getDescriptions()->remove($index);
                continue;
            }

            $this->fillProductDescriptionFront(
                $productFront,
                $productParser,
                $defaultLanguageFront,
                $productDescriptionFront
            );

            $needAdd = false;
        }

        if (true === $needAdd) {
            $productDescriptionFront = new ProductDescriptionFront();
            $productFront->getDescriptions()->add($productDescriptionFront);
            $this->fillProductDescriptionFront(
                $productFront,
                $productParser,
                $defaultLanguageFront,
                $productDescriptionFront
            );
        }
    }
}