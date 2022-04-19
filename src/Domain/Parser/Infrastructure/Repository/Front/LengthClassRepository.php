<?php

namespace App\Domain\Parser\Infrastructure\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\LengthClass;
use App\Domain\Common\Infrastructure\Repository\Base\Front\LengthClassRepository as Base;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\LengthClassProvider\Repository\Front\LengthClassRepository as LengthClassRepositoryLengthClassProvider;

class LengthClassRepository extends Base implements LengthClassRepositoryLengthClassProvider
{
    /**
     * @param int $id
     * @return LengthClass|null
     */
    public function findOne(int $id): ?LengthClass
    {
        return $this->find($id);
    }
}