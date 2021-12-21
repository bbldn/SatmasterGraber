<?php

namespace App\Domain\Common\Application\QueryBus;

use LogicException;
use Psr\Container\ContainerInterface;

class QueryBusImpl implements QueryBus
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
    public function execute($query)
    {
        $queryClassName = get_class($query);
        $queryHandlerClassName = "{$queryClassName}Handler";

        if (false === $this->container->has($queryHandlerClassName)) {
            throw new LogicException("Handler for {$queryClassName} not found");
        }

        /** @psalm-var QueryHandler */
        $queryHandler = $this->container->get($queryHandlerClassName);

        return $queryHandler($query);
    }
}