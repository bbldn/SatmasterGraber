<?php

namespace App\Domain\Parser\Application\CommandHandler;

use App\Domain\Parser\Domain\ValueObject\URL;
use Symfony\Component\HttpKernel\KernelInterface as Kernel;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\Arguments;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Domain\Parser\Application\Command\ParseCategoryProductsByCategoryURL;
use App\Domain\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Domain\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Domain\Parser\Application\Command\ParseCategoryProductsByCategoryURLHandler as Base;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class ParseCategoryProductsByCategoryURLHandler implements Base
{
    private Kernel $kernel;

    private ProductParser $productParser;

    private CategoryParser $categoryParser;

    private ProductToSQLGenerator $productToSQLGenerator;

    /**
     * @param Kernel $kernel
     * @param ProductParser $productParser
     * @param CategoryParser $categoryParser
     * @param ProductToSQLGenerator $productToSQLGenerator
     */
    public function __construct(
        Kernel $kernel,
        ProductParser $productParser,
        CategoryParser $categoryParser,
        ProductToSQLGenerator $productToSQLGenerator
    )
    {
        $this->kernel = $kernel;
        $this->productParser = $productParser;
        $this->categoryParser = $categoryParser;
        $this->productToSQLGenerator = $productToSQLGenerator;
    }

    /**
     * @param ParseCategoryProductsByCategoryURL $command
     * @return void
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    public function __invoke(ParseCategoryProductsByCategoryURL $command): void
    {
        $fileName = "{$this->kernel->getProjectDir()}/var/dumps/dump.sql";
        if (true === file_exists($fileName)) {
            unlink($fileName);
            touch($fileName);
        }

        $onInit = $command->getOnInit();
        $onStep = $command->getOnStep();
        $urls = $this->categoryParser->parse(new URL($command->getUrl()));
        if (null !== $onInit) {
            $onInit(count($urls));
        }

        file_put_contents($fileName, $this->productToSQLGenerator->sqlStartTransaction(), FILE_APPEND);

        foreach ($urls as $url) {
            $product = $this->productParser->parse(new URL($url));
            $arguments = new Arguments($product);
            $arguments->setCategoryId(62);
            $arguments->setImagePath('catalog/prod/graber/');
            $row = $this->productToSQLGenerator->generate($arguments);
            file_put_contents($fileName, $row, FILE_APPEND);

            if (null !== $onStep) {
                $onStep();
            }
        }

        file_put_contents($fileName, $this->productToSQLGenerator->sqlCommit(), FILE_APPEND);
    }
}