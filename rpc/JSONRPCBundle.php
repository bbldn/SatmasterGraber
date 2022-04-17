<?php

namespace BBLDN\JSONRPCBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\JSONRPCBundle\Bundle\Application\Symfony\DependencyInjection\Helper\Context;
use BBLDN\JSONRPCBundle\Bundle\Application\Symfony\DependencyInjection\Extension\JSONRPCExtension;
use BBLDN\JSONRPCBundle\Bundle\Application\Symfony\DependencyInjection\Compiler\JSONRPCRegistryPass;

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
        $container->addCompilerPass(new JSONRPCRegistryPass($context));
    }
}