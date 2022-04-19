<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer;

use DateTimeImmutable;
use App\Domain\Parser\Domain\DTO\Product as ProductParser;
use App\Domain\Common\Domain\Entity\Base\Front\Product as ProductFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ImageLoader\Loader as ImageLoader;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\WeightClassProvider\Provider as WeightClassProvider;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\LengthClassProvider\Provider as LengthClassProvider;

class Synchronizer
{
    private ImageLoader $imageLoader;

    private LengthClassProvider $lengthClassProvider;

    private WeightClassProvider $weightClassProvider;

    /**
     * @param ImageLoader $imageLoader
     * @param LengthClassProvider $lengthClassProvider
     * @param WeightClassProvider $weightClassProvider
     */
    public function __construct(
        ImageLoader $imageLoader,
        LengthClassProvider $lengthClassProvider,
        WeightClassProvider $weightClassProvider
    )
    {
        $this->imageLoader = $imageLoader;
        $this->lengthClassProvider = $lengthClassProvider;
        $this->weightClassProvider = $weightClassProvider;
    }

    /**
     * @param ProductFront $productFront
     * @return DateTimeImmutable
     */
    private function getDateAvailable(ProductFront $productFront): DateTimeImmutable
    {
        $dateAvailable = new DateTimeImmutable('1970-01-01');
        $dateAvailableProduct = $productFront->getDateAvailable();

        if (null === $dateAvailableProduct) {
            return $dateAvailable;
        }

        $condition = $dateAvailable->format('c') === $dateAvailableProduct->format('c');

        return true === $condition ? $dateAvailableProduct: $dateAvailable;
    }

    /**
     * @param ProductParser $productParser
     * @return string|null
     */
    private function getImage(ProductParser $productParser): ?string
    {
        $url = $productParser->getImage();
        if (null === $url) {
            return null;
        }

        return $this->imageLoader->loadByURL($url);
    }

    /**
     * @param ProductFront $productFront
     * @param ProductParser $productParser
     * @return void
     */
    public function synchronize(ProductFront $productFront, ProductParser $productParser): void
    {
        /** @var int $parserProductId */
        $parserProductId = $productParser->getId();

        $image = $this->getImage($productParser);
        $dateAvailable = $this->getDateAvailable($productFront);
        $weightClass = $this->weightClassProvider->getDefaultWeightClassFront();
        $lengthClass = $this->lengthClassProvider->getDefaultLengthClassFront();

        $productFront->setUpc('');
        $productFront->setEan('');
        $productFront->setJan('');
        $productFront->setMpn('');
        $productFront->setIsbn('');
        $productFront->setWidth(0);
        $productFront->setHeight(0);
        $productFront->setPoints(0);
        $productFront->setWeight(0);
        $productFront->setLength(0);
        $productFront->setViewed(0);
        $productFront->setStatus(true);
        $productFront->setLocation('');
        $productFront->setSortOrder(0);
        $productFront->setImage($image);
        $productFront->setMinimum(true);
        $productFront->setShipping(true);
        $productFront->setSubtract(true);
        $productFront->setQuantity(9999);
        $productFront->setTaxClass(null);
        $productFront->setStockStatus(null);
        $productFront->setManufacturer(null);
        $productFront->setLengthClass($lengthClass);
        $productFront->setWeightClass($weightClass);
        $productFront->setModel("art$parserProductId");
        $productFront->setSku((string)$parserProductId);
        $productFront->setDateAvailable($dateAvailable);
        $productFront->setPrice($productParser->getPrice());
    }
}