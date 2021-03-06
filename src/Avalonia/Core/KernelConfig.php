<?php
declare(strict_types=1);

namespace Avalonia\Core;

/**
 * Class KernelConfig
 * @package Avalonia\Core
 * @author Benjamin Perche <benjamin@perche.me>
 */
class KernelConfig
{
    private $environment;
    private $debug;
    private $cacheDir;

    public function __construct(
        string $environment,
        bool $debug,
        string $cacheDir
    ) {
        $this->environment = $environment;
        $this->debug = $debug;
        $this->cacheDir = $cacheDir;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return boolean
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return $this->cacheDir;
    }
}
