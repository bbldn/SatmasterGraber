<?php

namespace App\Domain\Api\Application\Command;

use App\Domain\Common\Application\CommandBus\Command;

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