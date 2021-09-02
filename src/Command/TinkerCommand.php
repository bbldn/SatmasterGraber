<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Context\Api\Application\Command\GenerateArchive;
use App\Context\Common\Application\CommandBus\CommandBus;

class TinkerCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'tinker';

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = new GenerateArchive('123', 'https://satmaster.kiev.ua/category/android-tv-box-596');
        $this->commandBus->execute($command);

        return Command::SUCCESS;
    }
}