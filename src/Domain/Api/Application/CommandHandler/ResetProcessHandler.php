<?php

namespace App\Domain\Api\Application\CommandHandler;

use App\Domain\Api\Domain\State\State;
use App\Domain\Api\Application\Command\ResetProcess;
use App\Domain\Api\Application\Common\State\File as StateFile;
use App\Domain\Api\Application\Command\ResetProcessHandler as Base;

class ResetProcessHandler implements Base
{
    /**
     * @param ResetProcess $command
     * @return State
     */
    public function __invoke(ResetProcess $command): State
    {
        $file = new StateFile("/tmp/graber/{$command->getUserId()}.json");
        $file->remove();
        $file->create();

        return $file->readState();
    }
}