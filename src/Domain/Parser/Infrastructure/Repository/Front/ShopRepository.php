<?php

namespace App\Domain\Parser\Infrastructure\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\Shop;
use App\Domain\Common\Infrastructure\Repository\Base\Front\ShopRepository as Base;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ShopProvider\Repository\Front\ShopRepository as ShopRepositoryShopProvider;

class ShopRepository extends Base implements ShopRepositoryShopProvider
{
    /**
     * @param int $id
     * @return Shop|null
     */
    public function findOne(int $id): ?Shop
    {
        return $this->find($id);
    }
}