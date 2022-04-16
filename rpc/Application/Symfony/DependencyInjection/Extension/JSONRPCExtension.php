<?php

namespace BBLDN\JSONRPC\Application\Symfony\DependencyInjection\Extension;

use BBLDN\JSONRPC\Application\Kernel;
use BBLDN\JSONRPC\Application\Hydrator\Hydrator;
use BBLDN\JSONRPC\Infrastructure\Resolver\Resolver;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\JSONRPC\Application\ResolverRegistry\ResolverRegistry;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use BBLDN\JSONRPC\Application\Symfony\DependencyInjection\Helper\Context;

class JSONRPCExtension implements ExtensionInterface
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
     * @return bool
     */
    public function getNamespace(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function getXsdValidationBasePath(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->context->getExtensionAlias();
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function definitionHydrator(ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setLazy(true);
        $definition->setClass(Hydrator::class);

        $container->setDefinition(Hydrator::class, $definition);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function definitionResolverRegistry(ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setLazy(true);
        $definition->setArgument(0, []);
        $definition->setClass(ResolverRegistry::class);

        $container->setDefinition(ResolverRegistry::class, $definition);
        $container->setAlias($this->context->getResolverRegistryAlias(), ResolverRegistry::class);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function definitionKernel(ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setLazy(true);
        $definition->setArgument(0, new Reference('service_container'));
        $definition->setArgument(1, $this->context->getResolverRegistryAlias());
        $definition->setClass(Kernel::class);

        $container->setDefinition(Kernel::class, $definition);
        $container->setAlias($this->context->getResolverKernelAlias(), ResolverRegistry::class);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function registerAutoconfiguration(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(Resolver::class)->addTag($this->context->getResolverTag());
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->registerAutoconfiguration($container);

        $this->definitionHydrator($container);
        $this->definitionResolverRegistry($container);
        $this->definitionKernel($container);
    }
}