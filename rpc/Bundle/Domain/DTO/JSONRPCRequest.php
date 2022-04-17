<?php

namespace BBLDN\JSONRPCBundle\Bundle\Domain\DTO;

class JSONRPCRequest
{
    private string $method;

    private ?array $params;

    /**
     * @var string|int|null
     */
    private $id;

    /**
     * @param string $method
     * @param array|null $params
     * @param int|string|null $id
     */
    public function __construct(string $method, ?array $params, $id)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }
}