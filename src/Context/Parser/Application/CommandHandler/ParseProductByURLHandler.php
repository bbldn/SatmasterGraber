<?php

namespace App\Context\Parser\Application\CommandHandler;

use App\Context\Parser\Domain\ValueObject\URL;
use Symfony\Component\HttpKernel\KernelInterface as Kernel;
use App\Context\Parser\Application\Command\ParseProductByURL;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Context\Parser\Application\Command\ParseProductByURLHandler as Base;
use App\Context\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Context\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

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
        $row = $this->productToSQLGenerator->generate($product, 62);
        file_put_contents($fileName, $row);
    }
}