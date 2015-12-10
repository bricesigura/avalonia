<?php
declare(strict_types=1);

namespace Avalonia\Core\DependencyInjection\Exception;

use Interop\Container\Exception\NotFoundException as BaseNotFoundException;

/**
 * Class NotFoundException
 * @package Avalonia\Core\DependencyInjection\Exception
 * @author Benjamin Perche <benjamin@perche.me>
 */
class NotFoundException extends ContainerException implements BaseNotFoundException
{
}
