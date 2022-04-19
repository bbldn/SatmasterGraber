<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\AttributeGroupProvider\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\AttributeGroup;

interface AttributeGroupRepository
{
    /**
     * @param int $id
     * @return AttributeGroup|null
     */
    public function findOne(int $id): ?AttributeGroup;
}