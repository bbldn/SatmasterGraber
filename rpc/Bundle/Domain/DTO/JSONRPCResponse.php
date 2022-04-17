<?php

namespace BBLDN\JSONRPCBundle\Bundle\Domain\DTO;

class JSONRPCResponse
{
    private $result;

    private $error;

    /**
     * @var string|int|null
     */
    private $id;

    /**
     * @param mixed $result
     * @param mixed $error
     * @param string|int|null $id
     */
    private function __construct($id, $result = null, $error = null)
    {
        $this->id = $id;
        $this->result = $result;
        $this->error = $error;
    }

    /**
     * @param mixed $result
     * @param string|int|null $id
     * @return JSONRPCResponse
     */
    public static function createSuccess($result, $id): JSONRPCResponse
    {
        return new self($id, $result, null);
    }

    /**
     * @param mixed $error
     * @param string|int|null $id
     * @return JSONRPCResponse
     */
    public static function createError($error, $id): JSONRPCResponse
    {
        return new self($id, null, $error);
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
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     *
     * @psalm-return array{jsonrpc: string, result: mixed, id: string|int|null}|array{jsonrpc: string, error: mixed, id: string|int|null}
     */
    public function toArray(): array
    {
        if (null !== $this->error) {
            return [
                'id' => $this->id,
                'jsonrpc' => '2.0',
                'error' => $this->error,
            ];
        }

        return [
            'id' => $this->id,
            'jsonrpc' => '2.0',
            'result' => $this->result,
        ];
    }
}