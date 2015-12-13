<?php
declare(strict_types = 1);

namespace Avalonia\Core\Tests\DependencyInjection;

use Avalonia\Core\DependencyInjection\DefaultContainer;
use Avalonia\Core\KernelInterface;
use Avalonia\Core\Tests\Mock\KernelMock;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class DefaultContainerTest
 * @package Avalonia\Core\Tests\DependencyInjection
 * @author Benjamin Perche <benjamin@perche.me>
 */
class DefaultContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultContainer
     */
    private $container;

    protected function setUp()
    {
        $this->container = new DefaultContainer(new KernelMock(), '/tmp/avalonia');
    }

    public function testContainerHasCoreServices()
    {

        $this->assertTrue($this->container->has('kernel'));
        $this->assertTrue($this->container->has('event_dispatcher'));

        $this->assertInstanceOf(KernelInterface::class, $this->container->get("kernel"));
        $this->assertInstanceOf(EventDispatcherInterface::class, $this->container->get("event_dispatcher"));
    }

    /**
     * @expectedException \Avalonia\Core\DependencyInjection\Exception\NotFoundException
     */
    public function testContainerThrowsNotFoundExceptionOnInvalidId()
    {
        $this->assertFalse($this->container->has('foo'));

        $this->container->get('foo');
    }
}
