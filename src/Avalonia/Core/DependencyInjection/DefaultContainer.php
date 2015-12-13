<?php
declare(strict_types=1);

namespace Avalonia\Core\DependencyInjection;

use Avalonia\Core\KernelInterface;
use Interop\Container\ContainerInterface;
use Avalonia\Core\DependencyInjection\Exception\NotFoundException;

/**
 * Class DefaultContainer
 * @package Avalonia\Core\DependencyInjection
 * @author Benjamin Perche <benjamin@perche.me>
 */
class DefaultContainer implements ContainerInterface
{
    /** @var KernelInterface */
    private $kernel;

    /** @var string */
    private $cacheDir;

    /**
     * DefaultContainer constructor.
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
     * @throws \Interop\Container\Exception\NotFoundException  No entry was found for this identifier.
     * @throws \Interop\Container\Exception\ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if ('kernel' === $id) {
            return $this->kernel;
        } elseif ('event_dispatcher' === $id) {
            return $this->kernel->getDispatcher();
        }

        throw new NotFoundException(sprintf("The service '%s' doesn't exist", $id));
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
        if (in_array($id, ['kernel', 'event_dispatcher'])) {
            return true;
        }

        return false;
    }
}
