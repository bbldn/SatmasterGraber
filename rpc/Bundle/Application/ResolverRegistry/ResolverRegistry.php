<?php

namespace BBLDN\JSONRPCBundle\Bundle\Application\ResolverRegistry;

class ResolverRegistry
{
    /**
     * array<'aliasName', [Resolver::class, 'methodName']>
     *
     * @psalm-var array<string, array{0: class-string, 1: string}>
     */
    private array $registry;

    /**
     * @param array $registry
     *
     * @psalm-param array<string, array{0: class-string, 1: string}> $registry
     */
    public function __construct(array $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return array
     *
     * @psalm-return array<string, array{0: class-string, 1: string}>
     */
    public function getRegistry(): array
    {
        return $this->registry;
    }
}