<?php
declare(strict_types=1);

namespace Avalonia\Core\DependencyInjection\Exception;

use Interop\Container\Exception\ContainerException as BaseContainerException;

/**
 * Class ContainerException
 * @package Avalonia\Core\DependencyInjection\Exception
 * @author Benjamin Perche <benjamin@perche.me>
 */
class ContainerException extends \Exception implements BaseContainerException
{
}
