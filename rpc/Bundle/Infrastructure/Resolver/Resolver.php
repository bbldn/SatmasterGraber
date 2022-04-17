<?php

namespace BBLDN\JSONRPCBundle\Bundle\Infrastructure\Resolver;

interface Resolver
{
    /**
     * @return string[]
     *
     * @psalm-return array<string, string>
     */
    public static function getAliases(): array;
}