<?php

namespace App\Context\Parser\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Context\Common\Application\CommandBus\CommandBus;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface as ContainerBag;
use App\Context\Parser\Application\Command\ParseCategoryProductsByCategoryURL;

class ParseProductsByCategoryIdCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'project:parse:products:by:category:id';

    private CommandBus $commandBus;

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

        $command = new ParseCategoryProductsByCategoryURL(
            $input->getArgument('url'),
            $onInit,
            $onStep
        );
        $this->commandBus->execute($command);

        return Command::SUCCESS;
    }
}