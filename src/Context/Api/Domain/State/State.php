<?php

namespace App\Context\Api\Domain\State;

use JsonSerializable;

interface State extends JsonSerializable
{
    /**
     * @return string
     */
    public static function getStep(): string;
}