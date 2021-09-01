<?php

namespace App\Context\Parser\Application\Common\ProductParser\AttributesParser;

use Symfony\Component\DomCrawler\Crawler;
use App\Context\Parser\Domain\DTO\Attribute;

class Parser
{
    /**
     * @param Crawler $crawler
     * @return Attribute[]
     *
     * @psalm-return list<Attribute>
     */
    public function parse(Crawler $crawler): array
    {
        foreach ($crawler->filter('#haracteristics .tech_chars dl')->children() as $c) {
            var_dump($c->nodeName);
        }

        return [];
    }
}