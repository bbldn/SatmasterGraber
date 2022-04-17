<?php

namespace BBLDN\JSONRPCBundle\Bundle\Application;

use Psr\Container\ContainerInterface as Container;
use BBLDN\JSONRPCBundle\Bundle\Domain\DTO\Arguments;
use BBLDN\JSONRPCBundle\Bundle\Domain\DTO\JSONRPCRequest;
use BBLDN\JSONRPCBundle\Bundle\Domain\DTO\JSONRPCResponse;
use BBLDN\JSONRPCBundle\Bundle\Domain\Exception\JSONRPCException;
use BBLDN\JSONRPCBundle\Bundle\Domain\Exception\MethodNotFoundException;
use BBLDN\JSONRPCBundle\Bundle\Application\ResolverRegistry\ResolverRegistry;

class Kernel
{
    private Container $container;

    private ResolverRegistry $resolverRegistry;

    /**
     * @param Container $container
     * @param ResolverRegistry $resolverRegistry
     */
    public function __construct(
        Container $container,
        ResolverRegistry $resolverRegistry
    )
    {
        $this->container = $container;
        $this->resolverRegistry = $resolverRegistry;
    }

    /**
     * @param JSONRPCRequest $request
     * @return JSONRPCResponse|null
     * @throws MethodNotFoundException
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    private function handleRequest(JSONRPCRequest $request): ?JSONRPCResponse
    {
        $aliasMap = $this->resolverRegistry->getRegistry();
        $method = $request->getMethod();
        if (false === key_exists($method, $aliasMap)) {
            throw new MethodNotFoundException("Resolver for method: \"$method\" not found");
        }

        $requestId = $request->getId();
        [$resolverClass, $method] = $aliasMap[$method];
        $arguments = new Arguments($request->getParams(), $requestId);

        /** @noinspection PhpUnhandledExceptionInspection */
        $resolverInstance = $this->container->get($resolverClass);

        try {
            $result = call_user_func([$resolverInstance, $method], $arguments);
        } catch (JSONRPCException $e) {
            if (null !== $requestId) {
                return JSONRPCResponse::createError($e->toArray(), $requestId);
            } else {
                return null;
            }
        }

        if (null !== $requestId) {
            return JSONRPCResponse::createSuccess($result, $requestId);
        }

        return null;
    }

    /**
     * @param JSONRPCRequest $request
     * @return JSONRPCResponse|null
     * @throws MethodNotFoundException
     */
    public function handle(JSONRPCRequest $request): ?JSONRPCResponse
    {
        return $this->handleRequest($request);
    }

    /**
     * @param JSONRPCRequest[] $requestList
     * @return JSONRPCResponse[]|null
     *
     * @psalm-param list<JSONRPCRequest> $requestList
     * @psalm-return list<JSONRPCResponse>|null
     * @throws JSONRPCException
     */
    public function handleList(array $requestList): ?array
    {
        $resultList = [];
        foreach ($requestList as $request) {
            $result = $this->handleRequest($request);
            if (null !== $result) {
                $resultList[] = $result;
            }
        }

        if (0 === count($resultList)) {
            return null;
        }

        return $resultList;
    }
}