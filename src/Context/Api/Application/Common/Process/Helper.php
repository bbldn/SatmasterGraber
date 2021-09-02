<?php

namespace App\Context\Api\Application\Common\Process;

use App\Context\Api\Domain\Step\Step;
use App\Context\Api\Domain\Step\NotRunning;
use App\Context\Api\Application\Common\Step\Hydrator;

class Helper
{
    /**
     * @param string $userId
     * @return Step
     */
    public function getStepByUserId(string $userId): Step
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