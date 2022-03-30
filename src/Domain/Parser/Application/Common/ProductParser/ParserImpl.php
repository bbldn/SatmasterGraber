<?php

namespace App\Domain\Parser\Application\Common\ProductParser;

use Symfony\Component\DomCrawler\Crawler;
use App\Domain\Parser\Domain\DTO\Product;
use App\Domain\Parser\Domain\DTO\Attribute;
use App\Domain\Parser\Domain\Exception\ParseException;
use App\Domain\Parser\Application\Common\ProductParser\AttributesParser\Parser as AttributesParser;

class ParserImpl implements Parser
{
    private AttributesParser $attributesParser;

    /**
     * @param AttributesParser $attributesParser
     */
    public function __construct(AttributesParser $attributesParser)
    {
        $this->attributesParser = $attributesParser;
    }

    /**
     * @param string $url
     * @return string
     * @throws ParseException
     */
    private function getContent(string $url): string
    {
        $html = @file_get_contents("https://am-parts.ru$url");
        if (false === $html) {
            throw new ParseException('Error');
        }

        return $html;
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
    private function parseImageList(Crawler $crawler): array
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
    private function parseAttributeList(Crawler $crawler, Product $product): array
    {
        $attributeList = $this->attributesParser->parse($crawler);
        foreach ($attributeList as $attribute) {
            $attribute->setProduct($product);
        }

        return $attributeList;
    }

    /**
     * @param string $url
     * @return Product
     * @throws ParseException
     */
    public function parse(string $url): Product
    {
        $html = $this->getContent($url);

        $crawler = new Crawler($html);

        $description = $this->parseDescription($crawler);

        $result = new Product();
        $result->setDescription($description);
        $result->setId($this->parseId($crawler));
        $result->setName($this->parseName($crawler));
        $result->setPrice($this->parsePrice($crawler));
        $result->setImages($this->parseImageList($crawler));
        $result->setAttributes($this->parseAttributeList($crawler, $result));

        return $result;
    }
}