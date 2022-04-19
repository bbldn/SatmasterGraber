<?php

namespace App\Domain\Common\Application\Config;

use Psr\Log\LoggerInterface as Logger;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface as Cache;
use App\Domain\Common\Application\Config\ConfigManager as Base;
use App\Domain\Common\Application\Config\Repository\Graber\ConfigRepository as ConfigGraberRepository;

class ConfigManagerImpl implements Base
{
    private const KEY = 'config_';

    private Cache $cache;

    private Logger $logger;

    private ConfigGraberRepository $configGraberRepository;

    /**
     * @param Cache $cache
     * @param Logger $logger
     * @param ConfigGraberRepository $configGraberRepository
     */
    public function __construct(
        Cache $cache,
        Logger $logger,
        ConfigGraberRepository $configGraberRepository
    )
    {
        $this->cache = $cache;
        $this->logger = $logger;
        $this->configGraberRepository = $configGraberRepository;
    }

    /**
     * @param string $key
     * @return string
     */
    private function getCacheKey(string $key): string
    {
        return static::KEY . $key;
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return null|string
     */
    public function getStringOrNull(string $key, $defaultValue = null): ?string
    {
        /** @psalm-suppress MissingClosureReturnType */
        $callback = static function () use ($key, $defaultValue) {
            $config = $this->configGraberRepository->findOneByKey($key);
            if (null === $config) {
                return $defaultValue;
            }

            return $config->getValue() ?? $defaultValue;
        };

        /** @psalm-suppress InvalidCatch */
        try {
            return $this->cache->get($this->getCacheKey($key), $callback) ?? $defaultValue;
        } catch (InvalidArgumentException $e) {
            /** @var string $e */
            $this->logger->error($e);

            return $defaultValue;
        }
    }

    /**
     * @param string $key
     * @param string $defaultValue
     * @return string
     */
    public function getString(string $key, string $defaultValue = ''): string
    {
        return $this->getStringOrNull($key, $defaultValue) ?? $defaultValue;
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return int|null
     */
    public function getIntOrNull(string $key, $defaultValue = null): ?int
    {
        $value = $this->getStringOrNull($key);
        if (null === $value) {
            return $defaultValue;
        }

        return (int)$value;
    }

    /**
     * @param string $key
     * @param int $defaultValue
     * @return int
     */
    public function getInt(string $key, int $defaultValue = 0): int
    {
        return $this->getIntOrNull($key, $defaultValue) ?? $defaultValue;
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return float|null
     */
    public function getFloatOrNull(string $key, $defaultValue = null): ?float
    {
        $value = $this->getStringOrNull($key);
        if (null === $value) {
            return $defaultValue;
        }

        return (float)$value;
    }

    /**
     * @param string $key
     * @param float $defaultValue
     * @return float
     */
    public function getFloat(string $key, float $defaultValue = 0.0): float
    {
        return $this->getFloatOrNull($key, $defaultValue) ?? $defaultValue;
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return string[]|null
     *
     * @psalm-return list<string>|null
     */
    public function getStringArrayOrNull(string $key, $defaultValue = null): ?array
    {
        $value = $this->getStringOrNull($key);
        if (null === $value) {
            return $defaultValue;
        }

        return explode(',', $value);
    }

    /**
     * @param string $key
     * @param string[] $defaultValue
     * @return string[]
     *
     * @psalm-param list<string> $defaultValue
     * @psalm-return list<string>
     */
    public function getStringArray(string $key, array $defaultValue = []): array
    {
        return $this->getStringArrayOrNull($key, $defaultValue) ?? $defaultValue;
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return int[]|null
     *
     * @psalm-return list<int>|null
     */
    public function getIntArrayOrNull(string $key, $defaultValue = null): ?array
    {
        $array = $this->getStringArrayOrNull($key);
        if (null === $array) {
            return $defaultValue;
        }

        return array_map('intval', $array);
    }

    /**
     * @param string $key
     * @param int[] $defaultValue
     * @return int[]
     *
     * @psalm-param list<int> $defaultValue
     * @psalm-return list<int>
     */
    public function getIntArray(string $key, array $defaultValue = []): array
    {
        return $this->getIntArrayOrNull($key, $defaultValue) ?? $defaultValue;
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return float[]|null
     *
     * @psalm-return list<float>|null
     */
    public function getFloatArrayOrNull(string $key, $defaultValue = null): ?array
    {
        $array = $this->getStringArrayOrNull($key);
        if (null === $array) {
            return $defaultValue;
        }

        return array_map('floatval', $array);
    }

    /**
     * @param string $key
     * @param float[] $defaultValue
     * @return float[]
     *
     * @psalm-param list<float> $defaultValue
     * @psalm-return list<float>
     */
    public function getFloatArray(string $key, array $defaultValue = []): array
    {
        return $this->getFloatArrayOrNull($key, $defaultValue) ?? $defaultValue;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        /** @psalm-suppress InvalidCatch */
        try {
            return $this->cache->delete($this->getCacheKey($key));
        } catch (InvalidArgumentException $e) {
            /** @var string $e */
            $this->logger->error($e);

            return false;
        }
    }
}