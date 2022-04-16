<?php

namespace BBLDN\JSONRPC\Application\Symfony\DependencyInjection\Extension;

use BBLDN\JSONRPC\Infrastructure\Resolver\Resolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
    private function registerAutoconfiguration(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(Resolver::class)->addTag($this->context->getResolverTag());
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->registerAutoconfiguration($container);
    }
}