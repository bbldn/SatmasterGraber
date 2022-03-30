<?php

namespace App\Domain\Parser\Application\Common\ProductParser;

use DOMDocument;
use DOMException;
use Symfony\Component\DomCrawler\Crawler;
use App\Domain\Parser\Domain\DTO\Product;
use App\Domain\Parser\Domain\Exception\ParseException;
use App\Domain\Parser\Application\Common\ProductParser\AttributesParser\Parser as AttributesParser;

class ParserImplDecorated implements Parser
{
    private ParserImpl $parserImpl;

    /**
     * @param AttributesParser $attributesParser
     */
    public function __construct(AttributesParser $attributesParser)
    {
        $this->parserImpl = new ParserImpl($attributesParser);
    }

    /**
     * @param Product $product
     * @return void
     * @throws DOMException
     */
    private function change(Product $product): void
    {
        $description = $product->getDescription();
        if (null === $description) {
            return;
        }

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML("<?xml encoding=\"utf-8\" ?>$description");
        $crawler = new Crawler($doc);

        /** Удаление ссылок */
        $closureA = static function (Crawler $c) use ($doc): void {
            foreach ($c as $child) {
                $nodeValue = $child->nodeValue;
                if (null !== $nodeValue) {
                    $node = $doc->createElement('p', $nodeValue);
                    $parentNode = $child->parentNode;
                    if (null !== $parentNode) {
                        $parentNode->replaceChild($node, $child);
                    }
                }
            }
        };
        $crawler->filter('a')->each($closureA);
        /** Конец */

        /** Удаление картинок из описания */
        $closureImg = static function (Crawler $c): void {
            foreach ($c as $child) {
                $parentNode = $child->parentNode;
                if (null !== $parentNode) {
                    $parentNode->removeChild($child);
                }
            }
        };
        $crawler->filter('img')->each($closureImg);
        /** Конец */

        /** Удаление пустых строк */
        $closureBr = static function (Crawler $c): void {
            foreach ($c as $child) {
                $parentNode = $child->parentNode;
                if (null !== $parentNode) {
                    $parentNode->removeChild($child);
                }
            }
        };
        $crawler->filter('br:nth-of-type(2n)')->each($closureBr);
        /** Конец */

        /** Удаление блока `attention` */
        $closureAttention = static function (Crawler $c): void {
            foreach ($c as $child) {
                $parentNode = $child->parentNode;
                if (null !== $parentNode) {
                    $parentNode->removeChild($child);
                }
            }
        };
        $crawler->filter('div.attention')->each($closureAttention);
        /** Конец */

        $product->setDescription($crawler->html());
    }

    /**
     * @param string $url
     * @return Product
     * @throws DOMException
     * @throws ParseException
     */
    public function parse(string $url): Product
    {
        $product = $this->parserImpl->parse($url);
        $this->change($product);

        return $product;
    }
}