<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\WeightClassProvider\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\WeightClass;

interface WeightClassRepository
{
    /**
     * @param int $id
     * @return WeightClass|null
     */
    public function findOne(int $id): ?WeightClass;
}