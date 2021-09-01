<?php

namespace App\Context\Parser\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Context\Common\Application\CommandBus\CommandBus;
use App\Context\Parser\Application\Command\DownloadProductsPicturesByCategoryURL;

class DownloadProductsPicturesByCategoryURLCommand extends Command
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

        $onInit = static function(int $number) use($progressBar): void {
            $progressBar->setMaxSteps($number);
        };

        $onStep = static function() use($progressBar): void {
            $progressBar->advance();
        };

        $command = new DownloadProductsPicturesByCategoryURL(
            $input->getArgument('url'),
            $onInit,
            $onStep
        );
        $this->commandBus->execute($command);

        $output->write(PHP_EOL);

        return Command::SUCCESS;
    }
}