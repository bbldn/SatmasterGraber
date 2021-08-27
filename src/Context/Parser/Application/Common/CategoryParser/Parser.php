<?php

namespace App\Context\Parser\Application\Common\CategoryParser;

use Symfony\Component\DomCrawler\Crawler;
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
     * @param string $url
     * @return string[]
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     *
     * @psalm-return list<string>
     */
    private function parsePagination(string $url): array
    {
        $html = $this->getContent($url);
        if (null === $html) {
            return [];
        }

        $closure = static fn(Crawler $c) => $c->attr('href');
        $data = (new Crawler($html))->filter('nav.page_nav a.addable')->each($closure);

        $result = [];
        foreach ($data as $item) {
            if ('JavaScript:;' !== $item) {
                $result[] = "https://satmaster.kiev.ua/{$item}";
            } else {
                $result[] = $url;
            }
        }

        return $result;
    }

    /**
     * @param string $html
     * @return string[]
     *
     * @psalm-return list<string>
     */
    private function parseProductsUrls(string $html): array
    {
        $closure = static fn(Crawler $c) => $c->attr('href');

        return (new Crawler($html))->filter('.item_block  .item_block_info .link_prod')->each($closure);
    }

    /**
     * @param string[] $urls
     * @return string[]
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     *
     * @psalm-return list<string>
     */
    private function parseHtml(array $urls): array
    {
        $result = [];
        foreach ($urls as $url) {
            $content = $this->getContent($url);

            $result = [
                ...$result,
                ...$this->parseProductsUrls($content),
            ];
        }

        return $result;
    }

    /**
     * @param URL $url
     * @return Result
     * @throws ServerExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    public function parse(URL $url): Result
    {
        $urls = $this->parsePagination($url->getUrl());

        return new Result($this->parseHtml($urls));
    }
}