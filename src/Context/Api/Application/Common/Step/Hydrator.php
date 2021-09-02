<?php

namespace App\Context\Api\Application\Common\Step;

use App\Context\Api\Domain\Step\Step;
use App\Context\Api\Domain\Step\Error;
use App\Context\Api\Domain\Step\Finish;
use App\Context\Api\Domain\Step\Process;
use App\Context\Api\Domain\Step\NotRunning;
use App\Context\Api\Domain\Step\Initialization;

class Hydrator
{
    /**
     * @param array $data
     * @return Step
     */
    public static function toStep(array $data): Step
    {
        switch ($data['step']) {
            case 'error':
                return new Error((string)($data['text'] ?? null), $data['code'] ?? null);
            case 'finish':
                return new Finish((string)($data['url'] ?? null), (string)($data['message'] ?? null));
            case 'initialization':
                return new Initialization((string)($data['message'] ?? null));
            case 'process':
                return new Process((int)($data['percent'] ?? 0), (string)($data['message'] ?? null));
            case 'not-running':
            default:
                return new NotRunning();
        }
    }
}