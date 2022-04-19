<?php

namespace App\Domain\Common\Infrastructure\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\AttributeDescription;
use App\Domain\Common\Infrastructure\Repository\Base\Front\AttributeDescriptionRepository as Base;
use App\Domain\Common\Application\Provider\AttributeProvider\Repository\Front\AttributeDescriptionRepository as AttributeDescriptionFrontRepository;

class AttributeDescriptionRepository extends Base implements AttributeDescriptionFrontRepository
{
    /**
     * @param string $name
     * @return AttributeDescription|null
     */
    public function findOneByName(string $name): ?AttributeDescription
    {
        return $this->findOneBy(['name' => $name]);
    }
}