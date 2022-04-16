<?php

namespace BBLDN\JSONRPC\Application\Symfony\DependencyInjection\Helper;

class Context
{
    private string $extensionAlias = 'bbldn.jsonrpc';

    private string $resolverTag = 'bbldn.jsonrpc.resolver';

    /**
     * @return string
     */
    public function getExtensionAlias(): string
    {
        return $this->extensionAlias;
    }

    /**
     * @return string
     */
    public function getResolverTag(): string
    {
        return $this->resolverTag;
    }
}