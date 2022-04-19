<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductImageListSynchronizer;

use App\Domain\Parser\Domain\DTO\Product as ProductParser;
use App\Domain\Common\Application\Helper\CollectionRebuilder;
use App\Domain\Common\Domain\Entity\Base\Front\Product as ProductFront;
use App\Domain\Common\Domain\Entity\Base\Front\ProductImage as ProductImageFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ImageLoader\Loader as ImageLoader;

class Synchronizer
{
    private ImageLoader $imageLoader;

    /**
     * @param ImageLoader $imageLoader
     */
    public function __construct(ImageLoader $imageLoader)
    {
        $this->imageLoader = $imageLoader;
    }

    /**
     * @param string $url
     * @param ProductFront $productFront
     * @param ProductImageFront $productImageFront
     * @return void
     */
    private function fillProductImageFront(
        string $url,
        ProductFront $productFront,
        ProductImageFront $productImageFront
    )
    {
        $image = $this->imageLoader->loadByURL($url);

        $productImageFront->setSortOrder(0);
        $productImageFront->setImage($image);
        $productImageFront->setProduct($productFront);
    }

    /**
     * @param ProductFront $productFront
     * @param ProductParser $productParser
     * @return void
     */
    public function synchronize(ProductFront $productFront, ProductParser $productParser): void
    {
        $productParserImageList = $productParser->getImages() ?? [];
        $productParserImageMapByFilename = CollectionRebuilder::rebuild('basename', $productParserImageList);
        foreach ($productFront->getProductImages() as $index => $productImageFront) {
            $key = basename((string)$productImageFront->getImage());
            if (false === key_exists($key, $productParserImageMapByFilename)) {
                $productFront->getProductImages()->remove($index);
                continue;
            }

            $this->fillProductImageFront($productParserImageMapByFilename[$key], $productFront, $productImageFront);
            unset($productParserImageMapByFilename[$key]);
        }

        foreach ($productParserImageMapByFilename as $url) {
            $productImageFront = new ProductImageFront();
            $productFront->getProductImages()->add($productImageFront);
            $this->fillProductImageFront($url, $productFront, $productImageFront);
        }
    }
}