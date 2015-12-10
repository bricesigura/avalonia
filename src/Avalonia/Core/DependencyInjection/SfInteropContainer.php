<?php
declare(strict_types=1);

namespace Avalonia\Core\DependencyInjection;

use Avalonia\Core\DependencyInjection\Exception\ContainerException;
use Avalonia\Core\DependencyInjection\Exception\NotFoundException;
use Interop\Container\ContainerInterface as InteropContainerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as SfContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class SfInteropContainer
 * @package Avalonia\Core\DependencyInjection
 * @author Benjamin Perche <benjamin@perche.me>
 */
class SfInteropContainer implements InteropContainerInterface
{
    private $container;

    public function __construct(SfContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        try {
            return $this->container->get($id);
        } catch (ServiceNotFoundException $e) {
            throw new NotFoundException(sprintf("The service '%s' doesn't exist", $id), null, $e);
        } catch (ServiceCircularReferenceException $e) {
            throw new ContainerException(sprintf("A circular reference has been found on service '%s'.", $id), null, $e);
        }
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * @return SfContainerInterface
     */
    public function getSfContainer()
    {
        return $this->container;
    }
}
