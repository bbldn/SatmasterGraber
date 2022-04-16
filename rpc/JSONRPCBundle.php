<?php

namespace BBLDN\JSONRPC;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\JSONRPC\Application\Symfony\DependencyInjection\Helper\Context;
use BBLDN\JSONRPC\Application\Symfony\DependencyInjection\Extension\JSONRPCExtension;

class JSONRPCBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        $context = new Context();

        $container->registerExtension(new JSONRPCExtension($context));
    }
}