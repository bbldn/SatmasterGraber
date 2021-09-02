<?php

namespace App\Context\Api\Domain\Step;

class NotRunning implements Step
{
    use StepTrait;

    /**
     * @return string
     */
    public function getStep(): string
    {
        return 'not-running';
    }
}