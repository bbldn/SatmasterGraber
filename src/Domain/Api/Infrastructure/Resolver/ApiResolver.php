<?php

namespace App\Domain\Api\Infrastructure\Resolver;

use App\Domain\Api\Domain\State\State;
use BBLDN\CQRSBundle\QueryBus\QueryBus;
use BBLDN\CQRSBundle\CommandBus\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use BBLDN\JSONRPCBundle\Bundle\Domain\DTO\Arguments;
use App\Domain\Api\Application\Command\StartProcess;
use App\Domain\Api\Application\Command\ResetProcess;
use App\Domain\Api\Application\Query\GetProcessState;
use BBLDN\JSONRPCBundle\Bundle\Infrastructure\Resolver\Resolver;

class ApiResolver implements Resolver
{
    private QueryBus $queryBus;

    private CommandBus $commandBus;

    /**
     * @param QueryBus $queryBus
     * @param CommandBus $commandBus
     */
    public function __construct(
        QueryBus $queryBus,
        CommandBus $commandBus
    )
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    /**
     * @return array
     *
     * @psalm-return array<string, string>
     */
    public static function getAliases(): array
    {
        return [
            'reset' => 'reset',
            'startProcess' => 'startProcess',
            'getProcessState' => 'getProcessState',
        ];
    }

    /**
     * @return string
     */
    private function getUserId(): string
    {
        $request = Request::createFromGlobals();
        $session = $request->getSession();
        if (false === $session->has('id')) {
            $userId = uniqid();
            $session->set('id', $userId);

            return $userId;
        }

        return (string)$session->get('id');
    }

    /**
     * @param Arguments $arguments
     * @return State
     */
    public function startProcess(Arguments $arguments): State
    {
        $userId = $this->getUserId();

        $params = $arguments->getParamList();
        $command = new StartProcess(
            $userId,
            $params[0] ?? '',
            $params[1] ?? null,
            $params[2] ?? null
        );

        return $this->commandBus->execute($command);
    }

    /**
     * @return State
     */
    public function getProcessState(): State
    {
        $userId = $this->getUserId();

        $query = new GetProcessState($userId);

        return $this->queryBus->execute($query);
    }

    /**
     * @return State
     */
    public function resetAction(): State
    {
        $userId = $this->getUserId();
        $command = new ResetProcess($userId);

        return $this->commandBus->execute($command);
    }
}