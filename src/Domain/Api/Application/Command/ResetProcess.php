<?php

namespace App\Domain\Api\Application\Command;

use BBLDN\CQRS\CommandBus\Command;
use BBLDN\CQRS\CommandBus\Annotation as CQRS;
use App\Domain\Api\Application\CommandHandler\ResetProcessHandler\CommandHandler;

/**
 * @CQRS\CommandHandler(class=CommandHandler::class)
 */
class ResetProcess implements Command
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