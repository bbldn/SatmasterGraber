<?php

namespace App\Context\Common\Domain\Arguments;

use Symfony\Component\HttpFoundation\Request;

class Arguments
{
    private ?array $params;

    private Request $request;

    private $id;

    /**
     * @param array|null $params
     * @param Request $request
     * @param null $id
     */
    public function __construct(
        ?array $params,
        Request $request,
        $id = null
    )
    {
        $this->params = $params;
        $this->request = $request;
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
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }
}