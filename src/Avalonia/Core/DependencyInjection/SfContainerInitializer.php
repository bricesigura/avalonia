<?php
declare(strict_types=1);

namespace Avalonia\Core\DependencyInjection;

use Avalonia\Core\Exception\TypeException;
use Avalonia\Core\KernelInterface;
use Avalonia\Core\Module\ModuleInterface;
use Interop\Container\ContainerInterface;
use Symfony\Component\Config\{ConfigCache, FileLocator, Loader\DelegatingLoader, Loader\LoaderResolver, Loader\LoaderInterface};
use Symfony\Component\DependencyInjection\{
    Container,
    ContainerBuilder,
    ContainerInterface as SfContainerInterface,
    Dumper\PhpDumper,
    Loader\ClosureLoader,
    Loader\DirectoryLoader,
    Loader\IniFileLoader,
    Loader\PhpFileLoader,
    Loader\XmlFileLoader,
    Loader\YamlFileLoader,
    ParameterBag\ParameterBag
};

// Optional classes
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper;

/**
 * Class SfContainerInitializer
 * @package Avalonia\Core\DependencyInjection
 * @author Benjamin Perche <benjamin@perche.me>
 */
class SfContainerInitializer implements ContainerInitializerInterface
{
    /**
     * @param ContainerBuilder $containerBuilder
     * @param KernelInterface $kernel
     * @return void
     * @throws TypeException
     *
     * Read the kernel modules, execute specific actions on them and store them into the kernel.
     */
    protected function prepareContainer(ContainerBuilder $containerBuilder, KernelInterface $kernel)
    {
        $modules = $kernel->getModules();

        foreach ($modules as $module) {
            if (!$module instanceof ModuleInterface) {
                throw new TypeException(ModuleInterface::class, $module);
            }

            if ($module instanceof SfContainerAwareModuleInterface) {
                $module->buildContainer($containerBuilder);
            }
        }

        $this->modules = $modules;
    }
    protected function getKernelParameters(KernelInterface $kernel)
    {
        return [
            'kernel.cache_dir' => $kernel->getConfig()->getCacheDir(),
        ];
    }

    /**
     * The following methods were taken/adapted from Symfony 3 kernel.
     * ==============================================================
     */

    /**
     * @return ContainerInterface
     *
     * Initialize the application container: builds kernel services and parameters.
     *
     * It must inject:
     * - services:
     *     - kernel: the kernel
     * - parameters:
     *     - kernel.cache_dir: the cache directory (from $kernel->getConfig()->getCacheDir())
     *
     * It should inject specific environment variable as parameters.
     */
    public function initializeContainer(KernelInterface $kernel): ContainerInterface
    {
        $config = $kernel->getConfig();
        $class = $this->getContainerClassName($config->getEnvironment(), $config->isDebug());
        $cache = new ConfigCache(
            $kernel->getConfig()->getCacheDir().DIRECTORY_SEPARATOR.$class.'.php',
            $kernel->getConfig()->isDebug()
        );

        $fresh = true;

        if (!$cache->isFresh()) {
            $container = $this->buildContainer($kernel);
            $container->compile();
            $this->dumpContainer($cache, $container, $class, $this->getContainerBaseClass(), $kernel);
            $fresh = false;
        }

        require_once $cache->getPath();

        /** @var SfContainerInterface $container */
        $container = new $class();
        $container->set('kernel', $kernel);

        if (!$fresh && $container->has('cache_warmer')) {
            $container->get('cache_warmer')->warmUp($this->container->getParameter('kernel.cache_dir'));
        }

        return new SfInteropContainer($container);
    }

    /**
     * Dumps the service container to PHP code in the cache.
     *
     * @param ConfigCache      $cache     The config cache
     * @param ContainerBuilder $container The service container
     * @param string           $class     The name of the class to generate
     * @param string           $baseClass The name of the container's base class
     * @param string           $kernel The kernel that is initializing the container
     */
    protected function dumpContainer(
        ConfigCache $cache,
        ContainerBuilder $container,
        string $class,
        string $baseClass,
        KernelInterface $kernel
    ) {
        // cache the container
        $dumper = new PhpDumper($container);
        if (class_exists('ProxyManager\Configuration') && class_exists('Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper')) {
            $dumper->setProxyDumper(new ProxyDumper(md5($cache->getPath())));
        }
        $content = $dumper->dump(array('class' => $class, 'base_class' => $baseClass, 'file' => $cache->getPath()));
        if (!$kernel->getConfig()->isDebug()) {
            $content = static::stripComments($content);
        }
        $cache->write($content, $container->getResources());
    }

    /**
     * Builds the service container.
     *
     * @param  KernelInterface $kernel The kernel that is initializing the container
     * @return ContainerBuilder The compiled service container
     *
     * @throws \RuntimeException
     */
    protected function buildContainer(KernelInterface $kernel)
    {
        $dir = $kernel->getConfig()->getCacheDir();

        if (!is_dir($dir)) {
            if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf("Unable to create the cache directory (%s)\n", $dir));
            }
        } elseif (!is_writable($dir)) {
            throw new \RuntimeException(sprintf("Unable to write in the cache directory (%s)\n", $dir));
        }

        $container = $this->getContainerBuilder($kernel);
        $container->addObjectResource($this);
        $this->prepareContainer($container, $kernel);

        if (null !== $cont = $this->registerContainerConfiguration($this->getContainerLoader($container))) {
            $container->merge($cont);
        }

        return $container;
    }

    /**
     * Gets a new ContainerBuilder instance used to build the service container.
     *
     * @param KernelInterface $kernel
     * @return ContainerBuilder
     */
    protected function getContainerBuilder(KernelInterface $kernel)
    {
        $containerBuilder = new ContainerBuilder(new ParameterBag($this->getKernelParameters($kernel)));
        if (class_exists('ProxyManager\Configuration') && class_exists('Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator')) {
            $containerBuilder->setProxyInstantiator(new RuntimeInstantiator());
        }
        return $containerBuilder;
    }

    protected function getContainerBaseClass()
    {
        return Container::class;
    }

    /**
     * Returns a loader for the container.
     *
     * @param SfContainerInterface $container The service container
     *
     * @return DelegatingLoader The loader
     */
    protected function getContainerLoader(SfContainerInterface $container)
    {
        $locator = new FileLocator($this);

        $resolver = new LoaderResolver(array(
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new DirectoryLoader($container, $locator),
            new ClosureLoader($container),
        ));

        return new DelegatingLoader($resolver);
    }

    protected function registerContainerConfiguration(LoaderInterface $loader)
    {
        // noop
    }

    /**
     * @param string $env
     * @param bool $debug
     * @return string
     */
    protected function getContainerClassName(string $env, bool $debug): string
    {
        return sprintf("AppContainer%s%s", $debug ? 'Debug':'', ucfirst(strtolower($env)));
    }

    /**
     * Removes comments from a PHP source string.
     *
     * We don't use the PHP php_strip_whitespace() function
     * as we want the content to be readable and well-formatted.
     *
     * @param string $source A PHP string
     *
     * @return string The PHP string with the comments removed
     */
    public static function stripComments($source)
    {
        if (!function_exists('token_get_all')) {
            return $source;
        }
        $rawChunk = '';
        $output = '';
        $tokens = token_get_all($source);
        $ignoreSpace = false;
        for (reset($tokens); false !== $token = current($tokens); next($tokens)) {
            if (is_string($token)) {
                $rawChunk .= $token;
            } elseif (T_START_HEREDOC === $token[0]) {
                $output .= $rawChunk.$token[1];
                do {
                    $token = next($tokens);
                    $output .= $token[1];
                } while ($token[0] !== T_END_HEREDOC);
                $rawChunk = '';
            } elseif (T_WHITESPACE === $token[0]) {
                if ($ignoreSpace) {
                    $ignoreSpace = false;
                    continue;
                }
                // replace multiple new lines with a single newline
                $rawChunk .= preg_replace(array('/\n{2,}/S'), "\n", $token[1]);
            } elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                $ignoreSpace = true;
            } else {
                $rawChunk .= $token[1];
                // The PHP-open tag already has a new-line
                if (T_OPEN_TAG === $token[0]) {
                    $ignoreSpace = true;
                }
            }
        }
        $output .= $rawChunk;
        return $output;
    }
}
