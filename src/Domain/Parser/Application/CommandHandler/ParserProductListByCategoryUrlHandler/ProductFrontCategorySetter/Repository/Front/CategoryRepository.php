<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductFrontCategorySetter\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\Category;

interface CategoryRepository
{
    /**
     * @param int $id
     * @return Category|null
     */
    public function findOne(int $id): ?Category;
}