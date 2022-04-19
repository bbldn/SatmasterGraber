<?php

namespace App\Domain\Parser\Infrastructure\Repository\Front;

use App\Domain\Common\Domain\Entity\Base\Front\Category;
use App\Domain\Common\Infrastructure\Repository\Base\Front\CategoryRepository as Base;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductFrontCategorySetter\Repository\Front\CategoryRepository as CategoryRepositoryProductFrontCategorySetter;

class CategoryRepository extends Base implements CategoryRepositoryProductFrontCategorySetter
{
    /**
     * @param int $id
     * @return Category|null
     */
    public function findOne(int $id): ?Category
    {
        return $this->find($id);
    }
}