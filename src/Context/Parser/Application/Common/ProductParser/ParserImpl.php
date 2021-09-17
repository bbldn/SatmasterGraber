<?php

namespace App\Context\Parser\Application\Common\ProductParser;

use Symfony\Component\DomCrawler\Crawler;
use App\Context\Parser\Domain\DTO\Product;
use App\Context\Parser\Domain\DTO\Attribute;
use App\Context\Parser\Domain\ValueObject\URL;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface as HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Context\Parser\Application\Common\ProductParser\AttributesParser\Parser as AttributesParser;

class ParserImpl implements Parser
{
    private HttpClient $httpClient;

    private AttributesParser $attributesParser;

    /**
     * @param HttpClient $httpClient
     * @param AttributesParser $attributesParser
     */
    public function __construct(
        HttpClient $httpClient,
        AttributesParser $attributesParser
    )
    {
        $this->httpClient = $httpClient;
        $this->attributesParser = $attributesParser;
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
        if (Response::HTTP_OK !== $response->getStatusCode()) {
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
     * @param Crawler $crawler
     * @param Product $product
     * @return Attribute[]
     *
     * @psalm-return list<Attribute>
     */
    private function parseAttributes(Crawler $crawler, Product $product): array
    {
        $attributes = $this->attributesParser->parse($crawler);
        foreach ($attributes as $attribute) {
            $attribute->setProduct($product);
        }

        return $attributes;
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

        $description = $this->parseDescription($crawler);

        $result = new Product();
        $result->setDescription($description);
        $result->setId($this->parseId($crawler));
        $result->setName($this->parseName($crawler));
        $result->setPrice($this->parsePrice($crawler));
        $result->setImages($this->parseImages($crawler));
        $result->setAttributes($this->parseAttributes($crawler, $result));

        return $result;
    }
}