<?php

namespace App\Domain\Common\Application\Helper;

class CollectionRebuilder
{
    /**
     * @param callable $callback
     * @param iterable $array
     * @return array
     */
    public static function rebuild(callable $callback, iterable $array): array
    {
        $result = [];
        foreach ($array as $item) {
            $key = call_user_func($callback, $item);
            if (null !== $key) {
                $result[$key] = $item;
            }
        }

        return $result;
    }
}