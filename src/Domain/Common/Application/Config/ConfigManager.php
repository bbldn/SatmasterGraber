<?php

namespace App\Domain\Common\Application\Config;

interface ConfigManager
{
    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return null|string
     */
    public function getStringOrNull(string $key, $defaultValue = null): ?string;

    /**
     * @param string $key
     * @param string $defaultValue
     * @return string
     */
    public function getString(string $key, string $defaultValue = ''): string;

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return int|null
     */
    public function getIntOrNull(string $key, $defaultValue = null): ?int;

    /**
     * @param string $key
     * @param int $defaultValue
     * @return int
     */
    public function getInt(string $key, int $defaultValue = 0): int;

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return float|null
     */
    public function getFloatOrNull(string $key, $defaultValue = null): ?float;

    /**
     * @param string $key
     * @param float $defaultValue
     * @return float
     */
    public function getFloat(string $key, float $defaultValue = 0.0): float;

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return string[]|null
     *
     * @psalm-return list<string>|null
     */
    public function getStringArrayOrNull(string $key, $defaultValue = null): ?array;

    /**
     * @param string $key
     * @param string[] $defaultValue
     * @return string[]
     *
     * @psalm-param list<string> $defaultValue
     * @psalm-return list<string>
     */
    public function getStringArray(string $key, array $defaultValue = []): array;

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return int[]|null
     *
     * @psalm-return list<int>|null
     */
    public function getIntArrayOrNull(string $key, $defaultValue = null): ?array;

    /**
     * @param string $key
     * @param int[] $defaultValue
     * @return int[]
     *
     * @psalm-param list<int> $defaultValue
     * @psalm-return list<int>
     */
    public function getIntArray(string $key, array $defaultValue = []): array;

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return float[]|null
     *
     * @psalm-return list<float>|null
     */
    public function getFloatArrayOrNull(string $key, $defaultValue = null): ?array;

    /**
     * @param string $key
     * @param float[] $defaultValue
     * @return float[]
     *
     * @psalm-param list<float> $defaultValue
     * @psalm-return list<float>
     */
    public function getFloatArray(string $key, array $defaultValue = []): array;

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;
}