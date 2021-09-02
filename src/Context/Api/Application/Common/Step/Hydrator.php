<?php

namespace App\Context\Api\Application\Common\Step;

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
                return new Error((string)$data['text'], $data['code']);
            case 'finish':
                return new Finish((string)$data['url'], (string)$data['message']);
            case 'initialization':
                return new Initialization((string)$data['message']);
            case 'process':
                return new Process((int)$data['percent'], (string)$data['message']);
            case 'not-running':
            default:
                return new NotRunning();
        }
    }
}