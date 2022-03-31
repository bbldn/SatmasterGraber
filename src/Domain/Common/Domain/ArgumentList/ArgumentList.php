<?php

namespace App\Domain\Common\Domain\ArgumentList;

use Symfony\Component\HttpFoundation\Request;

class ArgumentList
{
    private ?array $paramList;

    private Request $request;

    /** @var mixed */
    private $id;

    /**
     * @param array|null $paramList
     * @param Request $request
     * @param mixed $id
     */
    public function __construct(
        ?array $paramList,
        Request $request,
        $id
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}