<?php

namespace App\Context\Api\Application\Common\Process;

use App\Context\Api\Domain\State\State;
use App\Context\Api\Domain\State\NotRunning;
use App\Context\Api\Application\Common\State\Hydrator;

class Helper
{
    /**
     * @param string $userId
     * @return State
     */
    public function getStepByUserId(string $userId): State
    {
        $fileName = "/tmp/graber/$userId.json";
        if (false === file_exists($fileName)) {
            return new NotRunning();
        }

        $content = file_get_contents($fileName);
        $data = json_decode($content, true);

        return Hydrator::toStep($data);
    }
}