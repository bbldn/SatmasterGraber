<?php

namespace BBLDN\JSONRPC;

use BBLDN\JSONRPC\Helper\Context;
use BBLDN\JSONRPC\Resolver\Resolver;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class JSONRPCBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        $context = new Context();

        $container->registerForAutoconfiguration(Resolver::class)->addTag($context->getResolverTag());
    }
}