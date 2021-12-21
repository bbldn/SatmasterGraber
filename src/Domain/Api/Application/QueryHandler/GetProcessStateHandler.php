<?php

namespace App\Domain\Api\Application\QueryHandler;

use App\Domain\Api\Domain\State\State;
use App\Domain\Api\Application\Query\GetProcessState;
use App\Domain\Api\Application\Common\State\File as StateFile;
use App\Domain\Api\Application\Query\GetProcessStateHandler as Base;

class GetProcessStateHandler implements Base
{
    /**
     * @param GetProcessState $query
     * @return State
     */
    public function __invoke(GetProcessState $query): State
    {
        $file = new StateFile("/tmp/graber/{$query->getUserId()}.json");

        return $file->readState();
    }
}