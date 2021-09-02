<?php

namespace App\Context\Api\Infrastructure\Controller;

use Throwable;
use App\Context\Common\Domain\Arguments\Arguments;
use App\Context\Api\Application\Command\StartProcess;
use App\Context\Common\Application\QueryBus\QueryBus;
use App\Context\Api\Application\Query\GetProcessState;
use App\Context\Common\Domain\Response\JSONRPCResponse;
use App\Context\Common\Application\CommandBus\CommandBus;
use App\Context\Common\Infrastructure\Controller\JSONRPCController;

class FrontController extends JSONRPCController
{
    private QueryBus $queryBus;

    private CommandBus $commandBus;

    /**
     * @param QueryBus $queryBus
     * @param CommandBus $commandBus
     */
    public function __construct(
        QueryBus $queryBus,
        CommandBus $commandBus
    )
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    /**
     * @return array
     *
     * @psalm-return array<string, string>
     */
    public function getAliases(): array
    {
        return [
            'startProcess' => 'startProcess',
            'getProcessState' => 'getProcessState',
        ];
    }

    /**
     * @param Arguments $arguments
     * @return JSONRPCResponse
     */
    public function startProcess(Arguments $arguments): JSONRPCResponse
    {
        $session = $arguments->getRequest()->getSession();
        if (false === $session->has('id')) {
            $session->set('id', uniqid());
        }

        $params = $arguments->getParams();
        $command = new StartProcess(
            $params[0],
            $params[1],
            $params[2],
            $params[3]
        );

        try {
            $result = $this->commandBus->execute($command);

            return $this->jsonrpc($result, null, $arguments->getId());
        } catch (Throwable $e) {
            return $this->jsonrpc(null, $e->getMessage(), $arguments->getId());
        }
    }

    /**
     * @param Arguments $arguments
     * @return JSONRPCResponse
     */
    public function getProcessState(Arguments $arguments): JSONRPCResponse
    {
        $session = $arguments->getRequest()->getSession();
        if (false === $session->has('id')) {
            $userId = uniqid();
            $session->set('id', $userId);
        } else {
            $userId = (string)$session->get('id');
        }

        $query = new GetProcessState($userId);
        $result = $this->queryBus->execute($query);

        return $this->jsonrpc($result, null, $arguments->getId());
    }
}