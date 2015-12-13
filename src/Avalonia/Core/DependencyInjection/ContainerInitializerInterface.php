<?php
declare(strict_types=1);

namespace Avalonia\Core\DependencyInjection;

use Avalonia\Core\KernelInterface;
use Interop\Container\ContainerInterface;

/**
 * Interface ContainerInitializerInteface
 * @package Avalonia\Core\DependencyInjection
 * @author Benjamin Perche <benjamin@perche.me>
 */
interface ContainerInitializerInterface
{
    /**
     * @return ContainerInterface
     *
     * Initialize the application container: builds kernel services and parameters.
     *
     * It must inject:
     * - services:
     *     - kernel: the kernel
     *     - event_dispatcher: the kernel event dispatcher
     * - parameters: (If handles parameter)
     *     - kernel.cache_dir: the cache directory (from $kernel->getConfig()->getCacheDir())
     *
     * It should inject specific environment variable as parameters.
     */
    public function initializeContainer(KernelInterface $kernel): ContainerInterface;
}
