<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductSynchronizer\LengthClassProvider\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\LengthClass;

interface LengthClassRepository
{
    /**
     * @param int $id
     * @return LengthClass|null
     */
    public function findOne(int $id): ?LengthClass;
}