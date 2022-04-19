<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductUrlListByCategoryUrlParser;

use Symfony\Component\DomCrawler\Crawler;
use App\Domain\Parser\Domain\Exception\ParseException;

class Parser
{
    /**
     * @param string $url
     * @return string
     * @throws ParseException
     */
    private function getContent(string $url): string
    {
        $html = @file_get_contents($url);
        if (false === $html) {
            throw new ParseException('Error');
        }

        return $html;
    }

    /**
     * @param string $url
     * @return string[]
     * @throws ParseException
     *
     * @psalm-return list<string>
     *
     */
    private function parsePagination(string $url): array
    {
        $html = $this->getContent($url);

        /** @psalm-suppress MissingClosureReturnType */
        $closure = static fn(Crawler $c) => $c->attr('href');
        $itemList = (new Crawler($html))->filter('nav.page_nav a.addable')->each($closure);

        $result = [];
        foreach ($itemList as $item) {
            if ('JavaScript:;' !== $item) {
                $result[] = "https://satmaster.kiev.ua/$item";
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
    private function parseProductUrlList(string $html): array
    {
        $itemList = (new Crawler($html))->filter('.item_block')->each(static function(Crawler $crawler): array {
            if ('item_block notavailable' === $crawler->attr('class')) {
                return [];
            }

            return $crawler->filter(' .item_block_info .link_prod')->each(static fn(Crawler $c) => $c->attr('href'));
        });

        $result = [];
        foreach ($itemList as $item) {
            if (count($item) > 0) {
                $result[] = $item[0];
            }
        }

        return $result;
    }

    /**
     * @param string[] $urlList
     * @return string[]
     * @throws ParseException
     *
     * @psalm-param list<string> $urlList
     * @psalm-return list<string>
     */
    private function parseHtml(array $urlList): array
    {
        $result = [];
        foreach ($urlList as $url) {
            $content = $this->getContent($url);
            foreach ($this->parseProductUrlList($content) as $item) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @param string $url
     * @return string[]
     * @throws ParseException
     *
     * @psalm-return list<string>
     */
    public function parse(string $url): array
    {
        $urlList = $this->parsePagination($url);

        return $this->parseHtml($urlList);
    }
}