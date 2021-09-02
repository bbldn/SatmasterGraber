<?php

namespace App\Context\Api\Domain\State;

trait StateTrait
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