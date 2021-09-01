<?php

namespace App\Context\Parser\Application\Common\ProductToSQLGenerator\AttributeToSQLGenerator;

use App\Context\Parser\Domain\DTO\Attribute;

class Generator
{
    /**
     * @param Attribute $attribute
     * @return string
     */
    private function sqlId(Attribute $attribute): string
    {
        /**
         * @noinspection SqlDialectInspection
         * @noinspection SqlNoDataSourceInspection
         */
        $expression = sprintf('SELECT `attribute_id` FROM `oc_attribute_description` WHERE `name` = "%s" LIMIT 1', $attribute->getName());

        /**
         * @noinspection SqlDialectInspection
         * @noinspection SqlNoDataSourceInspection
         */
        $altValue = 'SELECT COALESCE(MAX(`attribute_id`), 0) + 1 FROM `oc_attribute`';

        return "SET @attributeId := IFNULL(($expression), ($altValue))";
    }

    /**
     * @return string
     */
    private function sqlAttribute(): string
    {
        $values = ['@attributeId', '1', '0'];
        $fields = ['`attribute_id`', '`attribute_group_id`', '`sort_order`'];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_attribute` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @param Attribute $attribute
     * @return string
     */
    private function sqlAttributeDescription(Attribute $attribute): string
    {
        $fields = ['`attribute_id`', '`language_id`', '`name`'];
        $values = ['@attributeId', '1', sprintf('"%s"', $attribute->getName())];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_attribute_description` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @param Attribute $attribute
     * @param string $productId
     * @return string
     */
    private function sqlProductAttribute(Attribute $attribute, string $productId): string
    {
        $fields = ['`product_id`', '`attribute_id`', '`language_id`', '`text`'];
        $values = [$productId, '@attributeId', '1', sprintf('"%s"', $attribute->getValue())];

        /** @noinspection SqlNoDataSourceInspection */
        return sprintf('REPLACE INTO `oc_product_attribute` (%s) VALUES (%s);', implode(', ', $fields), implode(', ', $values));
    }

    /**
     * @param Attribute $attribute
     * @param string $productId
     * @return string[]
     *
     * @psalm-return list<string>
     */
    public function generate(Attribute $attribute, string $productId): array
    {
        $product = $attribute->getProduct();
        if (null === $product) {
            return [];
        }

        return [
            $this->sqlId($attribute),
            $this->sqlAttribute(),
            $this->sqlAttributeDescription($attribute),
            $this->sqlProductAttribute($attribute, $productId),
        ];
    }
}