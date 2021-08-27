<?php

namespace App\Context\Parser\Application\Common\ProductParser;

use DOMDocument;
use Symfony\Component\DomCrawler\Crawler;
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
     * @param Product $product
     * @return void
     */
    private function change(Product $product): void
    {
        $description = $product->getDescription();
        if (null === $description) {
            return;
        }

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($description);
        $crawler = new Crawler($doc);

        $closure = static function (Crawler $c) use ($doc): void {
            foreach ($c as $child) {
                $node = $doc->createElement('p', $child->nodeValue);
                $child->parentNode->replaceChild($node, $child);
            }
        };

        $crawler->filter('a')->each($closure);
        $product->setDescription($crawler->html());
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
        $product = $this->parserImpl->parse($url);
        $this->change($product);

        return $product;
    }
}