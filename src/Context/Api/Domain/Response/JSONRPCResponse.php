<?php

namespace App\Context\Api\Domain\Response;

use JsonSerializable;

final class JSONRPCResponse implements JsonSerializable
{
    private $result = null;

    private $error = null;

    private $id = null;

    /**
     * @param mixed $result
     * @param mixed $error
     * @param mixed $id
     */
    public function __construct(
        $result = null,
        $error = null,
        $id = null
    )
    {
        $this->result = $result;
        $this->error = $error;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'jsonrpc' => 2.0,
            'result' => $this->result,
            'error' => $this->error,
            'id' => $this->id,
        ];
    }
}