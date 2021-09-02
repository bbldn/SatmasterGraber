<?php

namespace App\Context\Common\Application\QueryBus;

interface QueryBus
{
    /**
     * @param mixed $query
     * @return mixed
     */
    public function execute($query);
}