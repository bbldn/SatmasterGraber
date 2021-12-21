<?php

namespace App\Domain\Parser\Application\Common\ProductToSQLGenerator;

use App\Domain\Parser\Domain\DTO\Product;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\AttributeToSQLGenerator\Generator as AttributeToSQLGenerator;

class Generator
{
    private AttributeToSQLGenerator $attributeToSQLGenerator;

    /**
     * @param AttributeToSQLGenerator $attributeToSQLGenerator
     */
    public function __construct(AttributeToSQLGenerator $attributeToSQLGenerator)
    {
        $this->attributeToSQLGenerator = $attributeToSQLGenerator;
    }

    /**
     * @return string
     */
    public function sqlStartTransaction(): string
    {
        return 'START TRANSACTION;' . PHP_EOL . PHP_EOL;
    }

    /**
     * @return string
     */
    public function sqlCommit(): string
    {
        return 'COMMIT;';
    }

    /**
     * @param Product $product
     * @return string
     */
    private function sqlId(Product $product): string
    {
        /**
         * @noinspection SqlDialectInspection
         * @noinspection SqlNoDataSourceInspection
         */
        $expression = sprintf('SELECT `product_id` FROM `oc_product_description` WHERE `name` = "%s" LIMIT 1', $product->getName());

        /**
         * @noinspection SqlDialectInspection
         * @noinspection SqlNoDataSourceInspection
         */
        $altValue = 'SELECT COALESCE(MAX(`product_id`), 0) + 1 FROM `oc_product`';

        return "SET @productId := IFNULL(($expression), ($altValue));";
    }

    /**
     * @param Product $product
     * @param string|null $path
     * @return string
     */
    private function sqlProduct(Product $product, ?string $path): string
    {
        $fields = [
            '`product_id`',
            '`model`',
            '`sku`',
            '`upc`',
            '`ean`',
            '`jan`',
            '`isbn`',
            '`mpn`',
            '`location`',
            '`quantity`',
            '`stock_status_id`',
            '`image`',
            '`manufacturer_id`',
            '`shipping`',
            '`price`',
            '`points`',
            '`tax_class_id`',
            '`date_available`',
            '`weight`',
            '`weight_class_id`',
            '`length`',
            '`width`',
            '`height`',
            '`length_class_id`',
            '`subtract`',
            '`minimum`',
            '`sort_order`',
            '`status`',
            '`viewed`',
            '`date_added`',
            '`date_modified`',
            '`external_id`',
        ];

        $images = $product->getImages();
        if (null !== $path && null !== $images && count($images) > 0) {
            $pathParts = pathinfo($images[0]);
            $path = "$path{$pathParts['basename']}";
            $image = json_encode($path);
        } else {
            $image = '""';
        }

        $values = [
            '@productId', //product_id
            '@productId', //model
            '@productId', //sku
            '""', //upc
            '""', //ean
            '""', //jan
            '""', //isbn
            '""', //mpn
            '""', //location
            1, //quantity
            7, //stock_status_id
            $image, //image
            0, //manufacturer_id
            1, //shipping
            $product->getPrice() ?? 0.0, //price
            0, //points
            0, //tax_class_id
            json_encode('2021-01-01'), //date_available
            0, //weight
            1, //weight_class_id
            0, //length
            0, //width
            0, //height
            1, //length_class_id
            0, //subtract
            1, //minimum
            0, //sort_order
            1, //status
            0, //viewed
            json_encode('2021-01-01 00:00:00'), //date_added
            json_encode('2021-01-01 00:00:00'), //date_modified
            $product->getId() ?? 'null', //external_id
        ];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_product` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @param Product $product
     * @return string
     */
    private function sqlProductDescription(Product $product): string
    {
        $fields = [
            '`product_id`',
            '`language_id`',
            '`name`',
            '`description`',
            '`tag`',
            '`meta_title`',
            '`meta_description`',
            '`meta_keyword`',
        ];

        $values = [
            '@productId', //product_id
            1, //language_id
            json_encode($product->getName(), JSON_UNESCAPED_UNICODE), //name
            json_encode($product->getDescription(), JSON_UNESCAPED_UNICODE), //description
            '""', //tag
            '""', //meta_title
            '""', //meta_description
            '""', //meta_keyword
        ];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_product_description` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @return string
     */
    private function sqlProductStore(): string
    {
        $values = ['@productId', 0];
        $fields = ['`product_id`', '`store_id`'];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_product_to_store` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @param int $categoryId
     * @return string
     */
    private function sqlProductCategory(int $categoryId): string
    {
        $values = ['@productId', $categoryId];
        $fields = ['`product_id`', '`category_id`'];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_product_to_category` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @param Product $product
     * @param string $path
     * @return string[]
     *
     * @psalm-return list<string>
     */
    private function sqlProductImage(Product $product, string $path): array
    {
        $images = $product->getImages() ?? [];

        /**
         * @noinspection SqlDialectInspection
         * @noinspection SqlNoDataSourceInspection
         */
        $result = ['DELETE FROM `oc_product_image` WHERE product_id = @productId;'];
        foreach ($images as $image) {
            $pathParts = pathinfo($image);
            $image = "$path{$pathParts['basename']}";

            $values = ['@productId', json_encode($image), 0];
            $fields = ['`product_id`', '`image`', '`sort_order`'];

            /** @noinspection SqlNoDataSourceInspection */
            $result[] = sprintf('INSERT INTO `oc_product_image` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
        }

        return $result;
    }

    /**
     * @param Product $product
     * @return string[]
     *
     * @psalm-return list<string>
     */
    private function sqlAttributeList(Product $product): array
    {
        $attributes = $product->getAttributes() ?? [];

        $result = [];
        foreach ($attributes as $attribute) {
            $result = [
                ...$result,
                ...$this->attributeToSQLGenerator->generate($attribute),
            ];
        }

        return $result;
    }

    /**
     * @param ArgumentList $arguments
     * @return string
     */
    public function generate(ArgumentList $arguments): string
    {
        $path = $arguments->getImagePath();
        $product = $arguments->getProduct();

        $result = [
            $this->sqlId($product),
            $this->sqlProduct($product, $path),
            $this->sqlProductDescription($product),
            $this->sqlProductStore(),
            ...$this->sqlAttributeList($product),
        ];

        $categoryId = $arguments->getCategoryId();
        if (null !== $categoryId) {
            $result[] = $this->sqlProductCategory($categoryId);
        }

        if (null !== $path) {
            foreach ($this->sqlProductImage($product, $path) as $item) {
                $result[] = $item;
            }
        }

        $result[] = PHP_EOL;

        return implode(PHP_EOL, $result);
    }
}