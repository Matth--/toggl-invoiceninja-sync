<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Factory;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\CacheInterface;

final class CacheFactory
{
    public static function createCache(string $cacheDir): CacheInterface
    {
        return new FilesystemAdapter('', 0, $cacheDir);
    }
}
