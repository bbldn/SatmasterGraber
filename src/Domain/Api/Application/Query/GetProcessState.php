<?php

namespace App\Domain\Api\Application\Query;

use App\Domain\Common\Application\QueryBus\Query;

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