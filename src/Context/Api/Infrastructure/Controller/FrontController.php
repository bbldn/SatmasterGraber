<?php

namespace App\Context\Api\Infrastructure\Controller;

use App\Context\Api\Application\Query\GetProcessState;
use App\Context\Common\Domain\Arguments\Arguments;
use App\Context\Common\Application\QueryBus\QueryBus;
use App\Context\Common\Domain\Response\JSONRPCResponse;
use App\Context\Common\Infrastructure\Controller\JSONRPCController;

class FrontController extends JSONRPCController
{
    private QueryBus $queryBus;

    /**
     * @param QueryBus $queryBus
     */
    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @return array
     *
     * @psalm-return array<string, string>
     */
    public function getAliases(): array
    {
        return [
            'getProcessState' => 'getProcessState',
        ];
    }

    /**
     * @param Arguments $arguments
     * @return JSONRPCResponse
     */
    public function getProcessState(Arguments $arguments): JSONRPCResponse
    {
        $session = $arguments->getRequest()->getSession();
        $userId = (string)$session->get('id');

        $query = new GetProcessState($userId);
        $result = $this->queryBus->execute($query);

        return $this->jsonrpc($result, null, $arguments->getId());
    }
}