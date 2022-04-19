<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ProductProvider\Repository\Graber;

use App\Domain\Common\Domain\Entity\Base\Graber\Product;

interface ProductRepository
{
    /**
     * @param int|null $parserId
     * @return Product|null
     *
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function findOneByParserId(?int $parserId);
}