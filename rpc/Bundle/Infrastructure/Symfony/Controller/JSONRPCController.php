<?php

namespace BBLDN\JSONRPCBundle\Bundle\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use BBLDN\JSONRPCBundle\Bundle\Application\Hydrator\Hydrator;
use BBLDN\JSONRPCBundle\Bundle\Domain\Symfony\JSONRPCResponse;
use BBLDN\JSONRPCBundle\Bundle\Domain\Exception\JSONRPCException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use BBLDN\JSONRPCBundle\Bundle\Application\Kernel as JSONRPCKernel;

class JSONRPCController extends AbstractController
{
    private Hydrator $hydrator;

    private JSONRPCKernel $kernel;

    /**
     * @param Hydrator $hydrator
     * @param JSONRPCKernel $kernel
     */
    public function __construct(Hydrator $hydrator, JSONRPCKernel $kernel)
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