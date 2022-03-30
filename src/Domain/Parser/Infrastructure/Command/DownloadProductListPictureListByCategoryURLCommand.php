<?php

namespace App\Domain\Parser\Infrastructure\Command;

use BBLDN\CQRS\CommandBus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Domain\Parser\Application\Command\DownloadProductListPictureListByCategoryURL;

class DownloadProductListPictureListByCategoryURLCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'project:download:products:pictures:by:category:url';

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
        $progressBar = new ProgressBar($output);

        $command = new DownloadProductListPictureListByCategoryURL($input->getArgument('url'));

        /** @psalm-suppress MissingClosureReturnType */
        $command->setOnStep(static fn() => $progressBar->advance());

        /** @psalm-suppress MissingClosureReturnType */
        $command->setOnInit(static fn(int $max) => $progressBar->setMaxSteps($max));

        $this->commandBus->execute($command);

        $output->write(PHP_EOL);

        return Command::SUCCESS;
    }
}