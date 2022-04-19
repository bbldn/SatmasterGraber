<?php

namespace App\Domain\Parser\Application\Common\ProductParser\AttributeListParser;

use Symfony\Component\DomCrawler\Crawler;
use App\Domain\Parser\Domain\DTO\Attribute;

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
        $result = [];
        $crawler = $crawler->filter('#haracteristics .tech_chars dl');
        if (0 === $crawler->count()) {
            return $result;
        }

        $lastAttribute = null;
        foreach ($crawler->children() as $c) {
            switch ($c->nodeName) {
                case 'dt':
                    $lastAttribute = new Attribute();
                    $lastAttribute->setName($c->nodeValue);

                    break;
                case 'dd': {
                    /** @var Attribute $lastAttribute */
                    $lastAttribute->setValue($c->nodeValue);
                    $result[] = $lastAttribute;
                    $lastAttribute = null;

                    break;
                }
            }
        }

        return $result;
    }
}