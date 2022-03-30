<?php

namespace App\Domain\Api\Application\Query;

use BBLDN\CQRS\QueryBus\Query;
use BBLDN\CQRS\QueryBus\Annotation as CQRS;
use App\Domain\Api\Application\QueryHandler\GetProcessStateHandler\QueryHandler;

/**
 * @CQRS\QueryHandler(class=QueryHandler::class)
 */
class GetProcessState implements Query
{
    private string $userId;

    /**
     * @param string $userId
     */
    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}