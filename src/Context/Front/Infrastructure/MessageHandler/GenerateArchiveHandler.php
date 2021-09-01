<?php

namespace App\Context\Front\Infrastructure\MessageHandler;

use App\Context\Front\Domain\Message\GenerateArchive;
use App\Context\Common\Application\CommandBus\CommandBus;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Context\Front\Application\Command\GenerateArchive as GenerateArchiveCommand;

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