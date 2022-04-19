<?php

namespace App\Domain\Parser\Infrastructure\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\WeightClass;
use App\Domain\Common\Infrastructure\Repository\Base\Front\WeightClassRepository as Base;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\WeightClassProvider\Repository\Front\WeightClassRepository as WeightClassRepositoryWeightClassProvider;

class WeightClassRepository extends Base implements WeightClassRepositoryWeightClassProvider
{
    /**
     * @param int $id
     * @return WeightClass|null
     */
    public function findOne(int $id): ?WeightClass
    {
        return $this->find($id);
    }
}