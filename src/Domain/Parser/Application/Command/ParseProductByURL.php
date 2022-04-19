<?php

namespace App\Domain\Parser\Application\Command;

use BBLDN\CQRSBundle\CommandBus\Command;
use BBLDN\CQRSBundle\CommandBus\Annotation as CQRS;
use App\Domain\Parser\Application\CommandHandler\ParseProductByURLHandler\CommandHandler;

/**
 * @CQRS\CommandHandler(class=CommandHandler::class)
 */
class ParseProductByURL implements Command
{
    private string $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}