<?php

namespace App\Context\Api\Application\Common\Step;

use JsonSerializable;

interface Step extends JsonSerializable
{
    /**
     * @return string
     */
    public function getStep(): string;
}