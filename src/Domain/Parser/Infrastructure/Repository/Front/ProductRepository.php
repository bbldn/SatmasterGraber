<?php

namespace App\Domain\Parser\Infrastructure\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\Product;
use App\Domain\Common\Infrastructure\Repository\Base\Front\ProductRepository as Base;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ProductProvider\Repository\Front\ProductRepository as ProductRepositoryProductParserToFrontSynchronizer;

class ProductRepository extends Base implements ProductRepositoryProductParserToFrontSynchronizer
{
    /**
     * @param int $id
     * @return Product|null
     */
    public function findOne(int $id): ?Product
    {
        return $this->find($id);
    }
}