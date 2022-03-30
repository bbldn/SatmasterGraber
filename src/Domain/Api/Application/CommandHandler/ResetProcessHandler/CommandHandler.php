<?php

namespace App\Domain\Api\Application\CommandHandler\ResetProcessHandler;

use App\Domain\Api\Domain\State\State;
use App\Domain\Api\Application\Command\ResetProcess;
use App\Domain\Api\Application\Common\State\File as StateFile;

class CommandHandler
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