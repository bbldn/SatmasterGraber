<?php

namespace App\Context\Api\Domain\State;

class NotRunning implements State
{
    use StateTrait;

    /**
     * @return string
     */
    public static function getStep(): string
    {
        return 'not-running';
    }
}