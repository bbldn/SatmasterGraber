<?php

namespace App\Context\Api\Infrastructure\Controller;

use Throwable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Context\Common\Domain\Arguments\Arguments;
use App\Context\Api\Application\Command\StartProcess;
use App\Context\Common\Application\QueryBus\QueryBus;
use App\Context\Api\Application\Command\ResetProcess;
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
     * @return string
     */
    private function getUserId(Request $request): string
    {
        $session = $request->getSession();
        if (false === $session->has('id')) {
            $userId = uniqid();
            $session->set('id', $userId);

            return $userId;
        }

        return (string)$session->get('id');
    }

    /**
     * @param Arguments $arguments
     * @return JSONRPCResponse
     */
    public function startProcess(Arguments $arguments): JSONRPCResponse
    {
        $userId = $this->getUserId($arguments->getRequest());

        $params = $arguments->getParams();
        $command = new StartProcess(
            $userId,
            $params[0],
            $params[1] ?? null,
            $params[2] ?? null
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
        $userId = $this->getUserId($arguments->getRequest());

        $query = new GetProcessState($userId);
        $result = $this->queryBus->execute($query);

        return $this->jsonrpc($result, null, $arguments->getId());
    }

    /**
     * @param string $fileName
     * @return Response
     */
    public function archiveAction(string $fileName): Response
    {
        return $this->file("/tmp/graber/$fileName", $fileName);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function resetAction(Request $request): Response
    {
        $userId = $this->getUserId($request);
        $command = new ResetProcess($userId);

        try {
            $result = $this->commandBus->execute($command);

            return $this->jsonrpc($result);
        } catch (Throwable $e) {
            return $this->jsonrpc(null, $e->getMessage());
        }
    }
}