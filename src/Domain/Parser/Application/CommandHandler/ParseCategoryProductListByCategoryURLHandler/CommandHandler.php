<?php

namespace App\Domain\Parser\Application\CommandHandler\ParseCategoryProductListByCategoryURLHandler;

use App\Domain\Parser\Domain\Exception\ParseException;
use Symfony\Component\HttpKernel\KernelInterface as Kernel;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\Arguments;
use App\Domain\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Domain\Parser\Application\Command\ParseCategoryProductListByCategoryURL;
use App\Domain\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class CommandHandler
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
     * @param ParseCategoryProductListByCategoryURL $command
     * @return void
     * @throws ParseException
     */
    public function __invoke(ParseCategoryProductListByCategoryURL $command): void
    {
        $fileName = "{$this->kernel->getProjectDir()}/var/dumps/dump.sql";
        if (true === file_exists($fileName)) {
            unlink($fileName);
            touch($fileName);
        }

        $onInit = $command->getOnInit();
        $onStep = $command->getOnStep();
        $urlList = $this->categoryParser->parse($command->getUrl());
        if (null !== $onInit) {
            call_user_func($onInit, count($urlList));
        }

        file_put_contents($fileName, $this->productToSQLGenerator->sqlStartTransaction(), FILE_APPEND);

        foreach ($urlList as $url) {
            $product = $this->productParser->parse($url);
            $arguments = new Arguments($product);
            $arguments->setCategoryId(62);
            $arguments->setImagePath('catalog/prod/graber/');
            $row = $this->productToSQLGenerator->generate($arguments);
            file_put_contents($fileName, $row, FILE_APPEND);

            if (null !== $onStep) {
                call_user_func($onStep);
            }
        }

        file_put_contents($fileName, $this->productToSQLGenerator->sqlCommit(), FILE_APPEND);
    }
}