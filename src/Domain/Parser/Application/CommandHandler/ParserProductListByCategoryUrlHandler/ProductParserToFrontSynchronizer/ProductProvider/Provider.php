<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ProductProvider;

use App\Domain\Parser\Domain\DTO\Product as ProductParser;
use App\Domain\Common\Domain\Entity\Base\Front\Product as ProductFront;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ProductProvider\Repository\Front\ProductRepository as ProductFrontRepository;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ProductProvider\Repository\Graber\ProductRepository as ProductGraberRepository;

class Provider
{
    private ProductFrontRepository $productFrontRepository;

    private ProductGraberRepository $productGraberRepository;

    /**
     * @param ProductFrontRepository $productFrontRepository
     * @param ProductGraberRepository $productGraberRepository
     */
    public function __construct(
        ProductFrontRepository $productFrontRepository,
        ProductGraberRepository $productGraberRepository
    )
    {
        $this->productFrontRepository = $productFrontRepository;
        $this->productGraberRepository = $productGraberRepository;
    }

    /**
     * @param ProductParser $productParser
     * @return ProductFront|null
     */
    public function getProductFrontByProductParser(ProductParser $productParser): ?ProductFront
    {
        $parserId = $productParser->getId();
        if (null === $parserId) {
            return null;
        }

        $productGraber = $this->productGraberRepository->findOneByParserId($parserId);
        if (null === $productGraber) {
            return null;
        }

        $productFrontId = $productGraber->getFrontId();
        if (null === $productFrontId) {
            return null;
        }

        return $this->productFrontRepository->findOne($productFrontId);
    }
}