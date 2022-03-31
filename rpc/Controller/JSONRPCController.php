<?php

namespace BBLDN\JSONRPC\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Domain\Common\Domain\Response\JSONRPCResponse;
use App\Domain\Common\Domain\ArgumentList\ArgumentList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class JSONRPCController extends AbstractController
{
    /**
     * @var ContainerInterface
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected $container;

    protected abstract function getAliases(): array;

    /**
     * @param Request $request
     * @return JSONRPCResponse
     *
     * @noinspection PhpUnused
     */
    public function entryPoint(Request $request): JSONRPCResponse
    {
        $content = json_decode((string)$request->getContent(), true);

        $aliases = $this->getAliases();
        $method = $content['method'] ?? '';
        if (false === key_exists($method, $aliases)) {
            return $this->jsonrpc(null, "Method not found: $method", $content['id'] ?? null);
        }

        $method = $aliases[$method];
        $argumentList = new ArgumentList($content['params'] ?? null, $request, $content['id'] ?? null);

        return call_user_func([$this, $method], $argumentList);
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