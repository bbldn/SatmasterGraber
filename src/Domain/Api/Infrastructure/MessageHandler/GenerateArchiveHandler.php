<?php

namespace App\Domain\Api\Infrastructure\MessageHandler;

use BBLDN\CQRS\CommandBus\CommandBus;
use App\Domain\Api\Domain\Message\GenerateArchive;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Domain\Api\Application\Command\GenerateArchive as GenerateArchiveCommand;

class GenerateArchiveHandler implements MessageHandlerInterface
{
    private CommandBus $commandBus;

    /**
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param GenerateArchive $message
     * @return void
     */
    public function __invoke(GenerateArchive $message): void
    {
        $command = new GenerateArchiveCommand($message->getUserId(), $message->getSourceCategoryUrl());
        $command->setDestinationCategoryId($message->getDestinationCategoryId());
        $command->setDestinationImagesPath($message->getDestinationImagesPath());

        $this->commandBus->execute($command);
    }
}