<?php
declare(strict_types=1);

namespace Avalonia\Core\DependencyInjection;

use Avalonia\Core\KernelInterface;
use Interop\Container\ContainerInterface;

/**
 * Class NullContainerInitializer
 * @package Avalonia\Core\DependencyInjection
 * @author Benjamin Perche <benjamin@perche.me>
 */
class NullContainerInitializer implements ContainerInitializerInterface
{
    /**
     * @return ContainerInterface
     *
     * Initialize the application container: builds kernel services and parameters.
     *
     * It must inject:
     * - services:
     *     - kernel: the kernel
     * - parameters: (If handles parameter)
     *     - kernel.cache_dir: the cache directory (from $kernel->getConfig()->getCacheDir())
     *
     * It should inject specific environment variable as parameters.
     */
    public function initializeContainer(KernelInterface $kernel): ContainerInterface
    {
        return new NullContainer($kernel, $kernel->getConfig()->getCacheDir());
    }
}
