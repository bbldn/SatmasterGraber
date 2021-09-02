<?php

namespace App\Context\Common\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Context\Common\Domain\Arguments\Arguments;
use App\Context\Common\Domain\Response\JSONRPCResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class JSONRPCController extends AbstractController
{
    protected abstract function getAliases(): array;

    /**
     * @param Request $request
     * @return JSONRPCResponse
     */
    public function entryPoint(Request $request): JSONRPCResponse
    {
        $content = json_decode($request->getContent(), true);

        $aliases = $this->getAliases();
        $method = $content['method'] ?? '';
        if (false === key_exists($method, $aliases)) {
            return $this->jsonrpc(null, "Method not found: {$method}", $content['id'] ?? null);
        }

        $method = $aliases[$method];
        $arguments = new Arguments($content['params'] ?? null, $request, $content['id'] ?? null);

        return call_user_func([$this, $method], $arguments);
    }

    /**
     * @param mixed $result
     * @param mixed $error
     * @param mixed $id
     * @return JSONRPCResponse
     */
    protected function jsonrpc($result = null, $error = null, $id = null): JSONRPCResponse
    {
        return new JSONRPCResponse($result, $error, $id);
    }
}