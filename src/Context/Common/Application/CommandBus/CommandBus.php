<?php

namespace App\Context\Common\Application\CommandBus;

interface CommandBus
{
    /**
     * @param mixed $command
     * @return mixed
     */
    public function execute($command);
}