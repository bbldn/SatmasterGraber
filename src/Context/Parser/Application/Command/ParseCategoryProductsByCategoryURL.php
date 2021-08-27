<?php

namespace App\Context\Parser\Application\Command;

use App\Context\Common\Application\CommandBus\Command;

class ParseCategoryProductsByCategoryURL implements Command
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