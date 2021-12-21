<?php

namespace App\Domain\Parser\Application\Command;

use App\Domain\Common\Application\CommandBus\CommandHandler;

/**
 * @extends CommandHandler<ParseProductByURL, void>
 */
interface ParseProductByURLHandler extends CommandHandler
{
}