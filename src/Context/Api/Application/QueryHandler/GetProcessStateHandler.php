<?php

namespace App\Context\Api\Application\QueryHandler;

use App\Context\Api\Domain\State\State;
use App\Context\Api\Application\Query\GetProcessState;
use App\Context\Api\Application\Query\GetProcessStateHandler as Base;
use App\Context\Api\Application\Common\Process\Helper as ProcessHelper;

class GetProcessStateHandler implements Base
{
    private ProcessHelper $processHelper;

    /**
     * @param ProcessHelper $processHelper
     */
    public function __construct(ProcessHelper $processHelper)
    {
        $this->processHelper = $processHelper;
    }

    /**
     * @param GetProcessState $query
     * @return State
     */
    public function __invoke(GetProcessState $query): State
    {
        return $this->processHelper->getStepByUserId($query->getUserId());
    }
}