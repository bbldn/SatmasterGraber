<?php

namespace App\Context\Api\Application\Common\State;

use App\Context\Api\Domain\State\State;
use App\Context\Api\Domain\State\Error;
use App\Context\Api\Domain\State\Finish;
use App\Context\Api\Domain\State\Process;
use App\Context\Api\Domain\State\NotRunning;
use App\Context\Api\Domain\State\Initialization;

class Hydrator
{
    /**
     * @param array $data
     * @return State
     */
    public static function toStep(array $data): State
    {
        switch ($data['step'] ?? null) {
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