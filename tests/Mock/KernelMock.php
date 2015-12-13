<?php
declare(strict_types = 1);

namespace Avalonia\Core\Tests\Mock;

use Avalonia\Core\KernelConfig;
use Avalonia\Core\KernelInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class KernelMock
 * @package Avalonia\Core\Tests\Mock
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
        return new KernelConfig('test', true, '/tmp/avalonia');
    }

    /**
     * @return \Avalonia\Core\Module\ModuleInterface[]
     */
    public function getRegisteredModules(): array
    {
        return [];
    }

    /**
     * @return void
     */
    public function shutdown()
    {
        // noop
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher(): EventDispatcherInterface
    {
        return new EventDispatcher();
    }
}
