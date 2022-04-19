<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ProductProvider\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\Product;

interface ProductRepository
{
    /**
     * @param int $id
     * @return Product|null
     */
    public function findOne(int $id): ?Product;
}