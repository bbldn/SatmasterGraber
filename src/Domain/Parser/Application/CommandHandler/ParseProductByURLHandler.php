<?php

namespace App\Domain\Parser\Application\CommandHandler;

use App\Domain\Parser\Domain\ValueObject\URL;
use Symfony\Component\HttpKernel\KernelInterface as Kernel;
use App\Domain\Parser\Application\Command\ParseProductByURL;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\ArgumentList;
use App\Domain\Parser\Application\Command\ParseProductByURLHandler as Base;
use App\Domain\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class ParseProductByURLHandler implements Base
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
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    public function __invoke(ParseProductByURL $command): void
    {
        $fileName = "{$this->kernel->getProjectDir()}/var/dumps/dump.sql";

        $product = $this->productParser->parse(new URL($command->getUrl()));
        $arguments = new ArgumentList($product);
        $arguments->setCategoryId(62);
        $arguments->setImagePath('catalog/prod/graber/');
        $row = $this->productToSQLGenerator->generate($arguments);
        file_put_contents($fileName, $row);
    }
}