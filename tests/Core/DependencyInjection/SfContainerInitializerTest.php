<?php
declare(strict_types = 1);

namespace Avalonia\Tests\Core\DependencyInjection;

use Avalonia\Core\DependencyInjection\SfContainerInitializer;
use Avalonia\Core\DependencyInjection\SfInteropContainer;
use Avalonia\Tests\Core\Mock\KernelMock;

/**
 * Class SfContainerInitializerTest
 * @package Avalonia\Tests\Core\DependencyInjection
 * @author Benjamin Perche <benjamin@perche.me>
 */
class SfContainerInitializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SfContainerInitializer
     */
    private $containerInitializer;

    protected function setUp()
    {
        $this->containerInitializer = new SfContainerInitializer();
    }

    public function testInitializeContainerReturnAContainerWithTheKernel()
    {
        $kernel = new KernelMock();
        $container = $this->containerInitializer->initializeContainer($kernel);

        $this->assertInstanceOf(SfInteropContainer::class, $container);
        $this->assertEquals($kernel, $container->get('kernel'));
        $this->assertEquals(
            $kernel->getConfig()->getCacheDir(),
            $container->getSfContainer()->getParameter('kernel.cache_dir')
        );
    }
}
