<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler;

use App\Domain\Parser\Domain\Exception\ParseException;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Domain\Parser\Application\Command\ParserProductListByCategoryUrl;
use App\Domain\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductFrontCategorySetter\Setter as ProductFrontCategorySetter;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductUrlListByCategoryUrlParser\Parser as ProductUrlListByCategoryUrlParser;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer as ProductParserToFrontSynchronizer;

class CommandHandler
{
    private ProductParser $productParser;

    private EntityManager $entityManagerFront;

    private ProductFrontCategorySetter $productFrontCategorySetter;

    private ProductParserToFrontSynchronizer $productParserToFrontSynchronizer;

    private ProductUrlListByCategoryUrlParser $productUrlListByCategoryUrlParser;

    /**
     * @param ProductParser $productParser
     * @param EntityManager $entityManagerFront
     * @param ProductFrontCategorySetter $productFrontCategorySetter
     * @param ProductParserToFrontSynchronizer $productParserToFrontSynchronizer
     * @param ProductUrlListByCategoryUrlParser $productUrlListByCategoryUrlParser
     */
    public function __construct(
        ProductParser $productParser,
        EntityManager $entityManagerFront,
        ProductFrontCategorySetter $productFrontCategorySetter,
        ProductParserToFrontSynchronizer $productParserToFrontSynchronizer,
        ProductUrlListByCategoryUrlParser $productUrlListByCategoryUrlParser
    )
    {
        $this->productParser = $productParser;
        $this->entityManagerFront = $entityManagerFront;
        $this->productFrontCategorySetter = $productFrontCategorySetter;
        $this->productParserToFrontSynchronizer = $productParserToFrontSynchronizer;
        $this->productUrlListByCategoryUrlParser = $productUrlListByCategoryUrlParser;
    }

    /**
     * @param ParserProductListByCategoryUrl $command
     * @return void
     * @throws ParseException
     */
    public function __invoke(ParserProductListByCategoryUrl $command): void
    {
        $onInit = $command->getOnInit();
        $onStep = $command->getOnStep();

        $urlList = $this->productUrlListByCategoryUrlParser->parse($command->getURL());
        if (null !== $onInit) {
            call_user_func($onInit, count($urlList));
        }

        foreach ($urlList as $url) {
            $productParser = $this->productParser->parse($url);
            $productFront = $this->productParserToFrontSynchronizer->synchronize($productParser);
            $this->productFrontCategorySetter->set($productFront, $command->getCategoryFrontId());
            $this->entityManagerFront->flush();

            if (null !== $onStep) {
                call_user_func($onStep);
            }
        }
    }
}