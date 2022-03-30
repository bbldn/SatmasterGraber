<?php

namespace App\Domain\Parser\Application\CommandHandler\ParseProductByURLHandler;

use Symfony\Component\HttpKernel\KernelInterface as Kernel;
use App\Domain\Parser\Application\Command\ParseProductByURL;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\ArgumentList;
use App\Domain\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class CommandHandler
{
    private Kernel $kernel;

    private ProductParser $productParser;

    private ProductToSQLGenerator $productToSQLGenerator;

    /**
     * @param Kernel $kernel
     * @param ProductParser $productParser
     * @param ProductToSQLGenerator $productToSQLGenerator
     */
    public function __construct(
        Kernel $kernel,
        ProductParser $productParser,
        ProductToSQLGenerator $productToSQLGenerator
    )
    {
        $this->kernel = $kernel;
        $this->productParser = $productParser;
        $this->productToSQLGenerator = $productToSQLGenerator;
    }

    /**
     * @param ParseProductByURL $command
     * @return void
     */
    public function __invoke(ParseProductByURL $command): void
    {
        $fileName = "{$this->kernel->getProjectDir()}/var/dumps/dump.sql";

        $product = $this->productParser->parse($command->getUrl());
        $arguments = new ArgumentList($product);
        $arguments->setCategoryId(62);
        $arguments->setImagePath('catalog/prod/graber/');
        $row = $this->productToSQLGenerator->generate($arguments);
        file_put_contents($fileName, $row);
    }
}