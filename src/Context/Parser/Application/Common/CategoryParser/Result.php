<?php

namespace App\Context\Parser\Application\Common\CategoryParser;

class Result
{
    /**
     * @var string[]
     *
     * @psalm-var list<string>
     */
    private array $urls;

    /**
     * @param string[] $urls
     *
     * @psalm-param list<string> $urls
     */
    public function __construct(array $urls)
    {
        $this->urls = $urls;
    }

    /**
     * @return array
     *
     * @psalm-return list<string>
     */
    public function getUrls(): array
    {
        return $this->urls;
    }
}