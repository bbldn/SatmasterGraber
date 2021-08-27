<?php

namespace App\Context\Parser\Application\Common\ProductsToSQLGenerator;

use App\Context\Parser\Domain\DTO\Product;
use App\Context\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class Generator
{
    private ProductToSQLGenerator $productToSQLGenerator;

    /**
     * @param ProductToSQLGenerator $productToSQLGenerator
     */
    public function __construct(ProductToSQLGenerator $productToSQLGenerator)
    {
        $this->productToSQLGenerator = $productToSQLGenerator;
    }

    /**
     * @return string
     */
    private function sqlStartTransaction(): string
    {
        return 'START TRANSACTION;';
    }

    /**
     * @return string
     */
    private function sqlCommit(): string
    {
        return 'COMMIT;';
    }

    /**
     * @param Product[] $products
     * @return string
     *
     * @psalm-return list<Product>
     */
    public function generate(array $products): string
    {
        $values = array_map([$this->productToSQLGenerator, 'generate'], $products);

        return [
            $this->sqlStartTransaction(),
            ...$values,
            $this->sqlCommit(),
        ];
    }
}