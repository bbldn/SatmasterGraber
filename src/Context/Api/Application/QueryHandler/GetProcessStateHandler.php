<?php

namespace App\Context\Api\Application\QueryHandler;

use App\Context\Api\Domain\State\State;
use App\Context\Api\Application\Query\GetProcessState;
use App\Context\Api\Application\Common\State\File as StateFile;
use App\Context\Api\Application\Query\GetProcessStateHandler as Base;

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