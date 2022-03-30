<?php

namespace App\Domain\Parser\Application\Common\ProductParser;

use App\Domain\Parser\Domain\DTO\Product;

interface Parser
{
    /**
     * @param string $url
     * @return Product
     */
    public function parse(string $url): Product;
}