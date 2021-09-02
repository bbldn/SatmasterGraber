<?php

namespace App\Context\Api\Application\QueryHandler;

use App\Context\Api\Application\Query\GetState;
use App\Context\Api\Application\Common\Step\Step;
use App\Context\Api\Application\Common\Step\Hydrator;
use App\Context\Api\Application\Common\Step\NotRunning;
use App\Context\Api\Application\Query\GetStateHandler as Base;

class GetStateHandler implements Base
{
    /**
     * @param GetState $query
     * @return Step
     */
    public function __invoke(GetState $query): Step
    {
        $fileName = "/tmp/graber/{$query->getUserId()}.json";
        if (false === file_exists($fileName)) {
            return new NotRunning();
        }

        $content = file_get_contents($fileName);
        $data = json_decode($content, true);

        return Hydrator::toStep($data);
    }
}