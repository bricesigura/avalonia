<?php
declare(strict_types=1);

namespace Avalonia\Core\DependencyInjection;

use Avalonia\Core\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface SfContainerAwareModuleInterface
 * @package Avalonia\Core\Module
 * @author Benjamin Perche <benjamin@perche.me>
 */
interface SfContainerAwareModuleInterface extends ModuleInterface
{
    /**
     * @param ContainerBuilder $containerBuilder
     * @return void
     *
     * This method allows modules to
     */
    public function buildContainer(ContainerBuilder $containerBuilder);
}
