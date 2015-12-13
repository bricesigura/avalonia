<?php
declare(strict_types=1);

namespace Avalonia\Core\Module;

use Avalonia\Core\KernelInterface;

/**
 * Interface ModuleInterface
 * @package Avalonia\Core\Module
 * @author Benjamin Perche <benjamin@perche.me>
 */
interface ModuleInterface
{
    /**
     * @param KernelInterface $kernel
     * @return void
     *
     * This method is called when the kernel is booted
     */
    public function boot(KernelInterface $kernel);

    /**
     * @param KernelInterface $kernel
     * @return mixed
     *
     * This method is called when the kernel is shut down
     */
    public function shutdown(KernelInterface $kernel);
}
