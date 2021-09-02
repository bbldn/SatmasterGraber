<?php

namespace App\Context\Common\Domain\Arguments;

class Arguments
{
    private ?array $arguments;

    /**
     * @param array|null $arguments
     */
    public function __construct(?array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return array|null
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
    }
}