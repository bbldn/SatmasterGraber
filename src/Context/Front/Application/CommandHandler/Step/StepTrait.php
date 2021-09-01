<?php

namespace App\Context\Front\Application\CommandHandler\Step;

trait StepTrait
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = get_object_vars($this);
        $result['step'] = $this->getStep();

        return $result;
    }
}