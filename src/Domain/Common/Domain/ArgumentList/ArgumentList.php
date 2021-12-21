<?php

namespace App\Domain\Common\Domain\ArgumentList;

use Symfony\Component\HttpFoundation\Request;

class ArgumentList
{
    private ?array $paramList;

    private Request $request;

    private $id;

    /**
     * @param array|null $paramList
     * @param Request $request
     * @param null $id
     */
    public function __construct(
        ?array $paramList,
        Request $request,
        $id = null
    )
    {
        $this->paramList = $paramList;
        $this->request = $request;
        $this->id = $id;
    }

    /**
     * @return array|null
     */
    public function getParamList(): ?array
    {
        return $this->paramList;
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