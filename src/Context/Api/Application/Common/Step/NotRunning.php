<?php

namespace App\Context\Api\Application\Common\Step;

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