<?php

namespace BBLDN\JSONRPCBundle\Bundle\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use BBLDN\JSONRPCBundle\Bundle\Domain\DTO\JSONRPCResponse;
use BBLDN\JSONRPCBundle\Bundle\Application\Hydrator\Hydrator;
use BBLDN\JSONRPCBundle\Bundle\Domain\Exception\JSONRPCException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use BBLDN\JSONRPCBundle\Bundle\Application\Kernel as JSONRPCKernel;
use BBLDN\JSONRPCBundle\Bundle\Domain\Symfony\JSONRPCResponse as JSONRPCResponseSymfony;

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
     * @return JSONRPCResponseSymfony
     * @throws JSONRPCException
     */
    public function entryPoint(Request $request): JSONRPCResponseSymfony
    {
        try {
            $requestList = $this->hydrator->hydrate((string)$request->getContent());
        } catch (JSONRPCException $e) {
            $response = JSONRPCResponse::createError($e->toArray(), null);

            return new JSONRPCResponseSymfony($response);
        }

        if (true === is_array($requestList)) {
            $response = $this->kernel->handleList($requestList);
        } else {
            $response = $this->kernel->handle($requestList);
        }

        return new JSONRPCResponseSymfony($response);
    }
}