<?php

namespace App\Context\Front\Application\CommandHandler\Step;

use JsonSerializable;

abstract class Step implements JsonSerializable
{
    /**
     * @return string
     */
    public abstract function getStep(): string;

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