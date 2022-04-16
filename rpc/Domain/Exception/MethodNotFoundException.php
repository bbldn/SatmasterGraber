<?php

namespace BBLDN\JSONRPC\Domain\Exception;

class MethodNotFoundException extends JSONRPCException
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message, -32601);
    }
}