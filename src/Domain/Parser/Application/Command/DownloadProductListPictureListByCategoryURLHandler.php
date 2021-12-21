<?php

namespace App\Domain\Parser\Application\Command;

use App\Domain\Common\Application\CommandBus\CommandHandler;

/**
 * @extends CommandHandler<DownloadProductListPictureListByCategoryURL, void>
 */
interface DownloadProductListPictureListByCategoryURLHandler extends CommandHandler
{
}