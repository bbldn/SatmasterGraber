<?php

namespace App\Domain\Parser\Infrastructure\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\AttributeGroup;
use App\Domain\Common\Infrastructure\Repository\Base\Front\AttributeGroupRepository as Base;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\ProductAttributeListSynchronizer\AttributeParserToFrontSynchronizer\AttributeGroupProvider\Repository\Front\AttributeGroupRepository as AttributeGroupRepositoryAttributeGroupProvider;

class AttributeGroupRepository extends Base implements AttributeGroupRepositoryAttributeGroupProvider
{
    /**
     * @param int $id
     * @return AttributeGroup|null
     */
    public function findOne(int $id): ?AttributeGroup
    {
        return $this->find($id);
    }
}