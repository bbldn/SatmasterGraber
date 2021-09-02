<?php

namespace App\Context\Common\Domain\Arguments;

class Arguments
{
    private ?array $arguments;

    private $id;

    /**
     * @param array|null $arguments
     * @param null $id
     */
    public function __construct(?array $arguments, $id = null)
    {
        $this->arguments = $arguments;
        $this->id = $id;
    }

    /**
     * @return array|null
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }
}