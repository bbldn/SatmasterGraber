<?php

namespace App\Context\Api\Application\Query;

use App\Context\Common\Application\QueryBus\Query;

class GetState implements Query
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