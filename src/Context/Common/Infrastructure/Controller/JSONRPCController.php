<?php

namespace App\Context\Common\Infrastructure\Controller;

use Throwable;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use App\Context\Common\Domain\Arguments\Arguments;
use App\Context\Common\Domain\Response\JSONRPCResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class JSONRPCController extends AbstractController
{
    public abstract function getAliases(): array;

    /**
     * @param Request $request
     * @return JSONRPCResponse
     */
    public function entryPoint(Request $request): JSONRPCResponse
    {
        $aliases = $this->getAliases();
        $method = $request->get('method');
        if (false === key_exists($method, $aliases)) {
            throw new InvalidArgumentException("Method not found: {$method}");
        }

        $method = $aliases[$method];
        $arguments = new Arguments($request->get('arguments'));

        return call_user_func([$this, $method], $arguments);
    }

    /**
     * @param mixed $result
     * @param mixed $error
     * @param mixed $id
     * @return JSONRPCResponse
     * @throws Throwable
     */
    public function jsonrpc($result = null, $error = null, $id = null): JSONRPCResponse
    {
        return new JSONRPCResponse($result, $error, $id);
    }
}