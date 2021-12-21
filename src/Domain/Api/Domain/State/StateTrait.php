<?php

namespace App\Domain\Api\Domain\State;

trait StateTrait
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = get_object_vars($this);
        $result['step'] = static::getStep();

        return $result;
    }
}