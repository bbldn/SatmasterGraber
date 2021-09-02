<?php

namespace App\Context\Api\Infrastructure\Controller;

use App\Context\Common\Domain\Arguments\Arguments;
use App\Context\Common\Domain\Response\JSONRPCResponse;
use App\Context\Common\Infrastructure\Controller\JSONRPCController;

class FrontController extends JSONRPCController
{
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
        return $this->jsonrpc(null, null, $arguments->getId());
    }
}