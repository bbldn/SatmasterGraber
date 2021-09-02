<?php

namespace App\Context\Api\Application\CommandHandler;

use Exception;
use App\Context\Api\Domain\Step\Process;
use App\Context\Api\Domain\Step\Initialization;
use App\Context\Api\Domain\Message\GenerateArchive;
use App\Context\Api\Application\Command\StartProcess;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;
use App\Context\Api\Application\Command\StartProcessHandler as Base;
use App\Context\Api\Application\Common\Process\Helper as ProcessHelper;

class StartProcessHandler implements Base
{
    private MessageBus $messageBus;

    private ProcessHelper $processHelper;

    /**
     * @param MessageBus $messageBus
     * @param ProcessHelper $processHelper
     */
    public function __construct(
        MessageBus $messageBus,
        ProcessHelper $processHelper
    )
    {
        $this->messageBus = $messageBus;
        $this->processHelper = $processHelper;
    }

    /**
     * @param StartProcess $command
     * @return bool
     * @throws Exception
     */
    public function __invoke(StartProcess $command): bool
    {
        $userId = $command->getUserId();
        $step = $this->processHelper->getStepByUserId($userId);
        if (true === in_array(get_class($step), [Initialization::class, Process::class])) {
            throw new Exception('Process already running');
        }

        $message = new GenerateArchive($userId, $command->getSourceCategoryUrl());
        $message->setDestinationCategoryId($command->getDestinationCategoryId());
        $message->setDestinationImagesPath($command->getDestinationImagesPath());
        $this->messageBus->dispatch($message);

        return true;
    }
}