<?php

namespace App\Context\Parser\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Context\Common\Application\CommandBus\CommandBus;
use App\Context\Parser\Application\Command\ParseProductByURL;

class ParseProductByURLCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'project:parse:product:by:url';

    private CommandBus $commandBus;

    /**
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('url', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = new ParseProductByURL($input->getArgument('url'));
        $this->commandBus->execute($command);

        return Command::SUCCESS;
    }
}