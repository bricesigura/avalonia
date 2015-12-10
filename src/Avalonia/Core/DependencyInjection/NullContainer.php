<?php
declare(strict_types=1);

namespace Avalonia\Core\DependencyInjection;

use Avalonia\Core\KernelInterface;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;

/**
 * Class NullContainer
 * @package Avalonia\Core\DependencyInjection
 * @author Benjamin Perche <benjamin@perche.me>
 */
class NullContainer implements ContainerInterface
{
    /** @var KernelInterface */
    private $kernel;

    /** @var string */
    private $cacheDir;

    /**
     * NullContainer constructor.
     * @param KernelInterface $kernel
     * @param string $cacheDir
     */
    public function __construct(KernelInterface $kernel, string $cacheDir)
    {
        $this->kernel = $kernel;
        $this->cacheDir = $cacheDir;
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
        if ('kernel' === $id) {
            return $this->kernel;
        }

        return null;
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
        if ('kernel' === $id) {
            return $this->cacheDir;
        }

        return false;
    }
}
