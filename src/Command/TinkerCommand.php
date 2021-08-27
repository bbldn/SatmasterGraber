<?php

namespace App\Command;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TinkerCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'tinker';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $a = new Crawler('');
        return Command::SUCCESS;
    }
}