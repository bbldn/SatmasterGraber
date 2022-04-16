<?php

namespace BBLDN\JSONRPC\Application\Symfony\DependencyInjection\Helper;

class Context
{
    private string $extensionAlias = 'bbldn.jsonrpc';

    private string $resolverTag = 'bbldn.jsonrpc.resolver';

    private string $resolverKernelAlias = 'bbldn.jsonrpc.kernel';

    private string $resolverRegistryAlias = 'bbldn.jsonrpc.resolver_registry';

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

    /**
     * @return string
     */
    public function getResolverKernelAlias(): string
    {
        return $this->resolverKernelAlias;
    }

    /**
     * @return string
     */
    public function getResolverRegistryAlias(): string
    {
        return $this->resolverRegistryAlias;
    }
}