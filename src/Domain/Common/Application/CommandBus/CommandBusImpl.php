<?php

namespace App\Domain\Common\Application\CommandBus;

use LogicException;
use Psr\Container\ContainerInterface;

class CommandBusImpl implements CommandBus
{
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function execute($command)
    {
        $queryClassName = get_class($command);
        $queryHandlerClassName = "{$queryClassName}Handler";

        if (false === $this->container->has($queryHandlerClassName)) {
            throw new LogicException("Handler for {$queryClassName} not found");
        }

        /** @psalm-var CommandHandler */
        $queryHandler = $this->container->get($queryHandlerClassName);

        return $queryHandler($command);
    }
}