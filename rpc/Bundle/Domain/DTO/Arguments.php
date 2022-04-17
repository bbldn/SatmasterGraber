<?php

namespace BBLDN\JSONRPCBundle\Bundle\Domain\DTO;

class Arguments
{
    private ?array $paramList;

    /** @var string|int|null */
    private $id;

    /**
     * @param array|null $paramList
     * @param string|int|null $id
     */
    public function __construct(?array $paramList, $id)
    {
        $this->paramList = $paramList;
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
     * @return string|int|null
     */
    public function getId()
    {
        return $this->id;
    }
}