<?php

namespace App\Context\Api\Application\CommandHandler\Step;

use JsonSerializable;

interface Step extends JsonSerializable
{
    /**
     * @return string
     */
    public function getStep(): string;
}