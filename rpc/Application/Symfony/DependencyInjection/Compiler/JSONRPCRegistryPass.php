<?php

namespace BBLDN\JSONRPC\Application\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\JSONRPC\Application\ResolverRegistry\ResolverRegistry;
use BBLDN\JSONRPC\Application\Symfony\DependencyInjection\Helper\Context;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as CompilerPass;

class JSONRPCRegistryPass implements CompilerPass
{
    private Context $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $resolverMap = [];
        $serviceMap = $container->findTaggedServiceIds($this->context->getResolverTag());
        foreach ($serviceMap as $serviceId => $_) {
            $commandClassName = (string)$container->getDefinition($serviceId)->getClass();

            /** @psalm-var array<string, string> */
            $aliasMap = call_user_func("$commandClassName::getAliases");
            foreach ($aliasMap as $alias => $method) {
                if (true === key_exists($alias, $resolverMap)) {
                    throw new \LogicException("Alias: \"$alias\" already define");
                }

                $resolverMap[$alias] = [$commandClassName, $method];
            }
        }

        $definition = $container->getDefinition(ResolverRegistry::class);
        $definition->setArgument(0, $resolverMap);
    }
}