<?php

namespace App\Domain\Parser\Application\Command;

use App\Domain\Common\Application\CommandBus\CommandHandler;

/**
 * @extends CommandHandler<ParseCategoryProductListByCategoryURL, void>
 */
interface ParseCategoryProductListByCategoryURLHandler extends CommandHandler
{
}