<?php

namespace App\Domain\Common\Application\Provider\AttributeProvider\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\AttributeDescription;

interface AttributeDescriptionRepository
{
    /**
     * @param string $name
     * @return AttributeDescription|null
     */
    public function findOneByName(string $name): ?AttributeDescription;
}