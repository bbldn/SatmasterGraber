<?php

namespace App\Context\Api\Domain\Step;

use JsonSerializable;

interface Step extends JsonSerializable
{
    /**
     * @return string
     */
    public function getStep(): string;
}