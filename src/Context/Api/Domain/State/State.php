<?php

namespace App\Context\Api\Domain\State;

use JsonSerializable;

interface State extends JsonSerializable
{
    /**
     * @return string
     */
    public function getStep(): string;
}