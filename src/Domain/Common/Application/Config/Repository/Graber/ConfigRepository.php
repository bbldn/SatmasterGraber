<?php

namespace App\Domain\Common\Application\Config\Repository\Graber;

use App\Domain\Common\Domain\Entity\Base\Graber\Config;

interface ConfigRepository
{
    /**
     * @param string $key
     * @return Config|null
     */
    public function findOneByKey(string $key): ?Config;
}