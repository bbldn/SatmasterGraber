<?php

namespace App\Domain\Common\Application\QueryBus;

interface QueryBus
{
    /**
     * @param mixed $query
     * @return mixed
     */
    public function execute($query);
}