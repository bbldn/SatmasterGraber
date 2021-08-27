<?php

namespace App\Context\Parser\Application\Common\ProductParser;

use Symfony\Component\DomCrawler\Crawler;
use App\Context\Parser\Domain\DTO\Product;
use App\Context\Parser\Domain\ValueObject\URL;
use Symfony\Contracts\HttpClient\HttpClientInterface as HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class Parser
{
    private HttpClient $httpClient;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $url
     * @return string|null
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    private function getContent(string $url): ?string
    {
        $response = $this->httpClient->request('GET', $url);
        if (200 !== $response->getStatusCode()) {
            return null;
        }

        return $response->getContent(false);
    }

    /**
     * @param Crawler $crawler
     * @return int|null
     */
    private function parseId(Crawler $crawler): ?int
    {
        $node = $crawler->filter('input[name="productID"]')->first();
        if (0 === $node->count()) {
            return null;
        }

        return (int)$node->attr('value');
    }

    /**
     * @param Crawler $crawler
     * @return string|null
     */
    private function parseName(Crawler $crawler): ?string
    {
        $node = $crawler->filter('div.product_name h1')->first();
        if (0 === $node->count()) {
            return null;
        }

        return $node->text();
    }

    /**
     * @param Crawler $crawler
     * @return string|null
     */
    private function parseDescription(Crawler $crawler): ?string
    {
        $node = $crawler->filter('div#all')->first();
        if (0 === $node->count()) {
            return null;
        }

        return $node->html();
    }

    /**
     * @param Crawler $crawler
     * @return string[]
     *
     * @psalm-return list<string>
     */
    private function parseImages(Crawler $crawler): array
    {
        $closure = static fn(Crawler $c): string => $c->attr('href');

        return $crawler->filter('article.product-box div.items div.item a')->each($closure);
    }

    /**
     * @param Crawler $crawler
     * @return float|null
     */
    private function parsePrice(Crawler $crawler): ?float
    {
        $node = $crawler->filter('meta[itemprop="price"]')->first();
        if (0 === $node->count()) {
            return null;
        }

        return (float)$node->attr('content');
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
        $html = $this->getContent($url->getUrl());

        $crawler = new Crawler($html);

        $result = new Product();
        $result->setId($this->parseId($crawler));
        $result->setName($this->parseName($crawler));
        $result->setPrice($this->parsePrice($crawler));
        $result->setImages($this->parseImages($crawler));
        $result->setDescription($this->parseDescription($crawler));

        return $result;
    }
}