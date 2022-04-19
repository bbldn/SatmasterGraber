<?php

namespace App\Domain\Parser\Infrastructure\Repository\Graber;

use App\Domain\Common\Infrastructure\Repository\Base\Graber\ProductRepository as Base;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ProductProvider\Repository\Graber\ProductRepository as ProductRepositoryProductProvider;

class ProductRepository extends Base implements ProductRepositoryProductProvider
{
}