<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\Synchronizer\LayoutProvider\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\Layout;

interface LayoutRepository
{
    /**
     * @param int $id
     * @return Layout|null
     */
    public function findOne(int $id): ?Layout;
}