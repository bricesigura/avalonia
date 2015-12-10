<?php
declare(strict_types=1);

namespace Avalonia\Core;

use Avalonia\Core\DependencyInjection\ContainerInitializerInterface;
use Avalonia\Core\DependencyInjection\NullContainerInitializer;
use Avalonia\Core\Module\ModuleInterface;
use Interop\Container\ContainerInterface as InteropContainerInterface;


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

    public function __construct(KernelConfig $config, ContainerInitializerInterface $containerInitializer = null)
    {
        $this->config = $config;
        $this->containerInitializer = $containerInitializer ?: new NullContainerInitializer();
    }

    /**
     * @return void
     */
    public function boot()
    {
        if (!$this->isBooted()) {
            $this->container = $this->containerInitializer->initializeContainer($this);
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
    public function getModules(): array
    {
        return [];
    }

    /**
     * @return void
     */
    public function shutdown()
    {

    }
}
