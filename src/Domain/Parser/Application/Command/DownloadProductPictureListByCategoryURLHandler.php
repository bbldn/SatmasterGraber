<?php

namespace App\Domain\Parser\Application\Command;

use App\Domain\Common\Application\CommandBus\CommandHandler;

/**
 * @extends CommandHandler<DownloadProductPictureListByCategoryURL, void>
 */
interface DownloadProductPictureListByCategoryURLHandler extends CommandHandler
{
}