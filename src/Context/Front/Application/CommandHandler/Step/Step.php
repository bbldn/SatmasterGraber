<?php

namespace App\Context\Front\Application\CommandHandler\Step;

use JsonSerializable;

interface Step extends JsonSerializable
{
    /**
     * @return string
     */
    public function getStep(): string;
}