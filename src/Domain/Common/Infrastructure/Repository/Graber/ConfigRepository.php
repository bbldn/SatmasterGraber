<?php

namespace App\Domain\Common\Infrastructure\Repository\Graber;

use App\Domain\Common\Domain\Entity\Base\Graber\Config;
use App\Domain\Common\Infrastructure\Repository\Base\Graber\ConfigRepository as Base;
use App\Domain\Common\Application\Config\Repository\Graber\ConfigRepository as ConfigGraberRepository;

class ConfigRepository extends Base implements ConfigGraberRepository
{
    /**
     * @param string $key
     * @return Config|null
     */
    public function findOneByKey(string $key): ?Config
    {
        return $this->find($key);
    }
}