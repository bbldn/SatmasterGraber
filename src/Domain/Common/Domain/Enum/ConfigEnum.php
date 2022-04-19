<?php

namespace App\Domain\Common\Domain\Enum;

final class ConfigEnum
{
    /** URL для картинок товаров */
    public const PRODUCT_IMAGE_URL = 'PRODUCT_IMAGE_URL';

    /** Путь для сохранения картинок товаров */
    public const PRODUCT_IMAGE_PATH = 'PRODUCT_IMAGE_PATH';

    /** Front магазин по умолчанию */
    public const FRONT_DEFAULT_SHOP_ID = 'FRONT_DEFAULT_SHOP_ID';

    /** Front язык по умолчанию */
    public const FRONT_DEFAULT_LANGUAGE_ID = 'FRONT_DEFAULT_LANGUAGE_ID';

    /** Front класс длинны по умолчанию */
    public const FRONT_DEFAULT_LENGTH_CLASS_ID = 'FRONT_DEFAULT_LENGTH_CLASS_ID';

    /** Front класс веса по умолчанию */
    public const FRONT_DEFAULT_WEIGHT_CLASS_ID = 'FRONT_DEFAULT_WEIGHT_CLASS_ID';

    /** Front layout для товаров по умолчанию */
    public const FRONT_DEFAULT_PRODUCT_LAYOUT_ID = 'FRONT_DEFAULT_PRODUCT_LAYOUT_ID';

    /** Front группа аттрибутов по умолчанию */
    public const FRONT_DEFAULT_ATTRIBUTE_GROUP_ID = 'FRONT_DEFAULT_ATTRIBUTE_GROUP_ID';

    private function __construct()
    {
    }
}