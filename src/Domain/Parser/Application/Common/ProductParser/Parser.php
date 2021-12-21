<?php

namespace App\Domain\Parser\Application\Common\ProductParser;

use App\Domain\Parser\Domain\DTO\Product;
use App\Domain\Parser\Domain\ValueObject\URL;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

interface Parser
{
    /**
     * @param URL $url
     * @return Product
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    public function parse(URL $url): Product;
}