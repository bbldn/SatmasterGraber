<?php

namespace BBLDN\JSONRPC\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use BBLDN\JSONRPC\Application\Hydrator\Hydrator;
use BBLDN\JSONRPC\Domain\Symfony\JSONRPCResponse;
use BBLDN\JSONRPC\Domain\Exception\JSONRPCException;
use BBLDN\JSONRPC\Application\Kernel as JSONRPCKernel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JSONRPCController extends AbstractController
{
    private Hydrator $hydrator;

    private JSONRPCKernel $kernel;

    /**
     * @param Hydrator $hydrator
     * @param JSONRPCKernel $kernel
     */
    public function __construct(
        Hydrator $hydrator,
        JSONRPCKernel $kernel
    )
    {
        $this->kernel = $kernel;
        $this->hydrator = $hydrator;
    }

    /**
     * @param Request $request
     * @return JSONRPCResponse
     * @throws JSONRPCException
     */
    public function entryPoint(Request $request): JSONRPCResponse
    {
        $requestList = $this->hydrator->hydrate((string)$request->getContent());

        if (true === is_array($requestList)) {
            $response = $this->kernel->handleList($requestList);
        } else {
            $response = $this->kernel->handle($requestList);
        }

        return new JSONRPCResponse($response);
    }
}