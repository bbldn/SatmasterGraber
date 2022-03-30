<?php

namespace App\Domain\Api\Application\CommandHandler\StartProcessHandler;

use Exception;
use App\Domain\Api\Domain\State\State;
use App\Domain\Api\Domain\State\Process;
use App\Domain\Api\Domain\State\Initialization;
use App\Domain\Api\Domain\Message\GenerateArchive;
use App\Domain\Api\Application\Command\StartProcess;
use App\Domain\Api\Application\Common\State\File as StateFile;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;

class CommandHandler
{
    private MessageBus $messageBus;

    /**
     * @param MessageBus $messageBus
     */
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param StartProcess $command
     * @return State
     * @throws Exception
     */
    public function __invoke(StartProcess $command): State
    {
        $userId = $command->getUserId();
        $file = new StateFile("/tmp/graber/$userId.json");
        $class = get_class($file->readState());
        if (true === in_array($class, [Initialization::class, Process::class])) {
            throw new Exception('Process already running');
        }

        $file->whiteState(new Initialization('Ждем свободный обработчик'));
        $message = new GenerateArchive($userId, $command->getSourceCategoryUrl());
        $message->setDestinationCategoryId($command->getDestinationCategoryId());
        $message->setDestinationImagesPath($command->getDestinationImagesPath());
        $this->messageBus->dispatch($message);

        return $file->readState();
    }
}