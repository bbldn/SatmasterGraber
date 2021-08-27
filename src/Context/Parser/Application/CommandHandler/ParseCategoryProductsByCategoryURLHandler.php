<?php

namespace App\Context\Parser\Application\CommandHandler;

use App\Context\Parser\Domain\ValueObject\URL;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Context\Parser\Application\Command\ParseCategoryProductsByCategoryURL;
use App\Context\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Context\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Context\Parser\Application\Command\ParseCategoryProductsByCategoryURLHandler as Base;
use App\Context\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class ParseCategoryProductsByCategoryURLHandler implements Base
{
    private ProductParser $productParser;

    private CategoryParser $categoryParser;

    private ProductToSQLGenerator $productToSQLGenerator;

    /**
     * @param ProductParser $productParser
     * @param CategoryParser $categoryParser
     * @param ProductToSQLGenerator $productToSQLGenerator
     */
    public function __construct(
        ProductParser $productParser,
        CategoryParser $categoryParser,
        ProductToSQLGenerator $productToSQLGenerator
    )
    {
        $this->productParser = $productParser;
        $this->categoryParser = $categoryParser;
        $this->productToSQLGenerator = $productToSQLGenerator;
    }

    /**
     * @param ParseCategoryProductsByCategoryURL $command
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    public function __invoke(ParseCategoryProductsByCategoryURL $command): void
    {
        $fileName = '/var/dumps/dump.sql';
        if (true === file_exists($fileName)) {
            unlink($fileName);
        }

        $onInit = $command->getOnInit();
        $onStep = $command->getOnStep();
        $urls = $this->categoryParser->parse(new URL($command->getUrl()));
        if (null !== $onInit) {
            $onInit(count($urls));
        }

        foreach ($urls as $url) {
            $product = $this->productParser->parse(new URL($url));
            $row = $this->productToSQLGenerator->generate($product);
            file_put_contents($fileName, $row, FILE_APPEND);

            if (null !== $onStep) {
                $onStep();
            }
        }
    }
}