<?php

namespace App\Domain\Api\Infrastructure\Controller;

use Throwable;
use BBLDN\CQRS\CommandBus\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Api\Application\Command\ResetProcess;
use App\Domain\Common\Domain\Response\JSONRPCResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    private CommandBus $commandBus;

    /**
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param Request $request
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
     * @param string $fileName
     * @return Response
     */
    public function archiveAction(string $fileName): Response
    {
        return $this->file("/tmp/graber/$fileName", $fileName);
    }

    protected function jsonrpc($result = null, $error = null, $id = null): JSONRPCResponse
    {
        return new JSONRPCResponse($result, $error, $id);
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