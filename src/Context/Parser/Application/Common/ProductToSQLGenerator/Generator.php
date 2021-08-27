<?php

namespace App\Context\Parser\Application\Common\ProductToSQLGenerator;

use App\Context\Parser\Domain\DTO\Product;

class Generator
{
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
     * @param string $id
     * @return string
     */
    private function sqlReplaceProduct(Product $product, string $id): string
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
        if (null !== $images && count($images) > 0) {
            $pathParts = pathinfo($images[0]);
            $path = "catalog/prod/graber/{$pathParts['basename']}";
            $image = json_encode($path);
        } else {
            $image = '""';
        }

        $values = [
            $id, //product_id
            $id, //model
            $id, //sku
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
            $product->getPrice(), //price
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
            $product->getId(), //external_id
        ];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_product` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @param Product $product
     * @param string $id
     * @return string
     */
    private function sqlReplaceProductDescription(Product $product, string $id): string
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
            $id, //product_id
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
     * @param string $id
     * @return string
     */
    private function sqlReplaceProductStore(string $id): string
    {
        $values = [$id, 0];
        $fields = ['`product_id`', '`store_id`'];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_product_to_store` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @param string $id
     * @return string
     */
    private function sqlReplaceProductCategory(string $id): string
    {
        $values = [$id, 61];
        $fields = ['`product_id`', '`category_id`'];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_product_to_category` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @param Product $product
     * @param string $id
     * @return string[]
     *
     * @psalm-return list<string>
     */
    private function sqlReplaceProductImage(Product $product, string $id): array
    {
        $images = $product->getImages() ?? [];

        /**
         * @noinspection SqlDialectInspection
         * @noinspection SqlNoDataSourceInspection
         */
        $result = [sprintf('DELETE FROM `oc_product_image` WHERE product_id = %s;', $id)];
        foreach ($images as $image) {
            $pathParts = pathinfo($image);
            $image = "catalog/prod/graber/{$pathParts['basename']}";

            $values = [$id, json_encode($image), 0];
            $fields = ['`product_id`', '`image`', '`sort_order`'];

            /** @noinspection SqlNoDataSourceInspection */
            $result[] = sprintf('INSERT INTO `oc_product_image` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
        }

        return $result;
    }

    /**
     * @param Product $product
     * @return string
     */
    public function generate(Product $product): string
    {
        $id = "1000{$product->getId()}";

        $result = [
            $this->sqlReplaceProduct($product, $id),
            $this->sqlReplaceProductDescription($product, $id),
            $this->sqlReplaceProductStore($id),
            $this->sqlReplaceProductCategory($id),
            ...$this->sqlReplaceProductImage($product, $id),
            PHP_EOL,
        ];

        return implode(PHP_EOL, $result);
    }
}