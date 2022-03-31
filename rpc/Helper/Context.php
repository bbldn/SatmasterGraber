<?php

namespace BBLDN\JSONRPC\Helper;

class Context
{
    private string $resolverTag;

    public function __construct()
    {
        $this->resolverTag = 'bbldn.jsonrpc.resolver';
    }

    /**
     * @return string
     */
    public function getResolverTag(): string
    {
        return $this->resolverTag;
    }
}