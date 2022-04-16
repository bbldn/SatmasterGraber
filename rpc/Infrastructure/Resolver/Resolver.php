<?php

namespace BBLDN\JSONRPC\Infrastructure\Resolver;

interface Resolver
{
    /**
     * @return string[]
     *
     * @psalm-return array<string, string>
     */
    public static function getAliases(): array;
}