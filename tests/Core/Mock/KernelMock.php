<?php
declare(strict_types = 1);

namespace Avalonia\Tests\Core\Mock;

use Avalonia\Core\KernelConfig;
use Avalonia\Core\KernelInterface;

/**
 * Class KernelMock
 * @package Avalonia\Tests\Core
 * @author Benjamin Perche <benjamin@perche.me>
 */
class KernelMock implements KernelInterface
{
    /**
     * @return void
     */
    public function boot()
    {
        // noop
    }

    /**
     * @return bool
     *
     * Indicates if the kernel has been booted.
     */
    public function isBooted(): bool
    {
        return true;
    }

    /**
     * @return KernelConfig
     */
    public function getConfig(): KernelConfig
    {
        return new KernelConfig('test', true, '/tmp/');
    }

    /**
     * @return \Avalonia\Core\Module\ModuleInterface[]
     */
    public function getModules(): array
    {
        return [];
    }
}
