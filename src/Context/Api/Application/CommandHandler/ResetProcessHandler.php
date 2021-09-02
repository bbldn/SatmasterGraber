<?php

namespace App\Context\Api\Application\CommandHandler;

use App\Context\Api\Domain\State\State;
use App\Context\Api\Application\Command\ResetProcess;
use App\Context\Api\Application\Common\State\File as StateFile;
use App\Context\Api\Application\Command\ResetProcessHandler as Base;

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