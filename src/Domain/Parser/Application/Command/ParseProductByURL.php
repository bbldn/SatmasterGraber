<?php

namespace App\Domain\Parser\Application\Command;

use App\Domain\Common\Application\CommandBus\Command;

class ParseProductByURL implements Command
{
    private string $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}