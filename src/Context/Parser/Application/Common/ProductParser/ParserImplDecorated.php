<?php

namespace App\Context\Parser\Application\Common\ProductParser;

use App\Context\Parser\Domain\DTO\Product;
use App\Context\Parser\Domain\ValueObject\URL;
use Symfony\Contracts\HttpClient\HttpClientInterface as HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class ParserImplDecorated implements Parser
{
    private ParserImpl $parserImpl;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->parserImpl = new ParserImpl($httpClient);
    }

    /**
     * @param URL $url
     * @return Product
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    public function parse(URL $url): Product
    {
        return $this->parserImpl->parse($url);
    }
}