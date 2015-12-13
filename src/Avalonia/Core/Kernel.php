<?php
declare(strict_types=1);

namespace Avalonia\Core;

use Avalonia\Core\DependencyInjection\{ContainerInitializerInterface, DefaultContainerInitializer};
use Avalonia\Core\Exception\TypeException;
use Avalonia\Core\Module\ModuleInterface;
use Interop\Container\ContainerInterface as InteropContainerInterface;
use Symfony\Component\EventDispatcher\{EventDispatcher, EventDispatcherInterface};

/**
 * Class Kernel
 * @package Avalonia\Core
 * @author Benjamin Perche <benjamin@perche.me>
 */
class Kernel implements KernelInterface
{
    /** @var bool */
    private $booted;

    /** @var ModuleInterface[] */
    private $modules;

    /** @var InteropContainerInterface */
    private $container;

    /** @var KernelConfig */
    private $config;

    /** @var ContainerInitializerInterface */
    private $containerInitializer;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function __construct(
        KernelConfig $config,
        ContainerInitializerInterface $containerInitializer = null,
        EventDispatcherInterface $dispatcher = null
    ) {
        $this->config = $config;
        $this->containerInitializer = $containerInitializer ?: new DefaultContainerInitializer();
        $this->dispatcher = $dispatcher ?: new EventDispatcher();
    }

    /**
     * @return void
     */
    public function boot()
    {
        if (!$this->isBooted()) {
            $this->prepareModules();
            $this->container = $this->containerInitializer->initializeContainer($this);

            foreach ($this->modules as $module) {
                $module->boot($this);
            }

            $this->booted = true;
        }
    }

    /**
     * @return bool
     *
     * Indicates if the kernel has been booted.
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * @return KernelConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return ModuleInterface[]
     */
    public function getRegisteredModules(): array
    {
        return $this->modules ?? [];
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @return InteropContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return void
     */
    public function shutdown()
    {
        foreach ($this->modules as $module) {
            $module->shutdown($this);
        }
    }

    /**
     * @return ModuleInterface[]
     */
    protected function getModules(): array
    {
        return [];
    }

    private function prepareModules()
    {
        $modules = $this->getModules();

        foreach ($modules as $module) {
            if (!$module instanceof ModuleInterface) {
                throw new TypeException(ModuleInterface::class, $module);
            }
        }

        $this->modules = $modules;
    }
}
