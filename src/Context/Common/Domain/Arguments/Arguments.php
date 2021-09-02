<?php

namespace App\Context\Common\Domain\Arguments;

class Arguments
{
    private ?array $params;

    private $id;

    /**
     * @param array|null $params
     * @param null $id
     */
    public function __construct(?array $params, $id = null)
    {
        $this->params = $params;
        $this->id = $id;
    }

    /**
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }
}