<?php

namespace App\Context\Api\Domain\Step;

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