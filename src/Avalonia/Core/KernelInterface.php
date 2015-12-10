<?php
declare(strict_types=1);

namespace Avalonia\Core;

/**
 * Interface KernelInterface
 * @package Avalonia\Core
 * @author Benjamin Perche <benjamin@perche.me>
 */
interface KernelInterface
{
    /**
     * @return void
     */
    public function boot();

    /**
     * @return void
     */
    public function shutdown();

    /**
     * @return bool
     *
     * Indicates if the kernel has been booted.
     */
    public function isBooted(): bool;

    /**
     * @return KernelConfig
     */
    public function getConfig(): KernelConfig;

    /**
     * @return \Avalonia\Core\Module\ModuleInterface[]
     */
    public function getModules(): array;
}
