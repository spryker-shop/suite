<?php

namespace Spryker\Shared\SymfonyContainer;

use Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfig;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

trait ContainerTrait
{
    protected static ?\Symfony\Component\DependencyInjection\Container $symfonyContainer = null;

    /**
     * @template T
     * @param string|class-string<T> $className
     *
     * @return mixed|T
     */
    protected function create(string $className)
    {
        if (static::$symfonyContainer === null) {
            $this->initContainer();
        }

        return static::$symfonyContainer->get(ltrim($className, '\\'));
    }

    /**
     * @param string|class-string $className
     *
     * @return bool
     */
    protected function has(string $className): bool
    {
        if (static::$symfonyContainer === null) {
            $this->initContainer();
        }

        return static::$symfonyContainer->has(ltrim($className, '\\'));
    }

    /**
     * @throws \Spryker\Client\Kernel\ClassResolver\Client\ClientNotFoundException
     * @return void
     */
    private function initContainer(): void
    {
        $isDebug = true;
        $applicationType = APPLICATION;
        $applicationType = ucfirst(strtolower($applicationType));
        $codeBucket = (new CodeBucketConfig())->getCurrentCodeBucket();
        $cacheClassName = 'CachedContainer';

        $file = APPLICATION_ROOT_DIR . '/data/cache/di/' . $applicationType . '/' . $codeBucket . '/' . $cacheClassName . '.php';
        $containerConfigCache = new ConfigCache($file, $isDebug);

        if (!$containerConfigCache->isFresh()) {
            $container = new ContainerBuilder();

            // Locate your PHP config directory
            $locator = new FileLocator(APPLICATION_ROOT_DIR . '/config');
            $loader = new PhpFileLoader($container, $locator);

            $moduleFinder = (new \Spryker\Zed\Kernel\Business\KernelBusinessFactory)->createModuleNamesFinder();
            $clientResolver = new \Spryker\Client\Kernel\ClassResolver\Client\ClientResolver();
            foreach ($moduleFinder->findModuleNames() as $module) {
                $interfaceName = 'Spryker\\Client\\' . $module . '\\' . $module . 'ClientInterface';
                if (!interface_exists($interfaceName)) {
                    continue;
                }
                $className = get_class($clientResolver->resolve($interfaceName));
                $container->register($className);
                $container->register($interfaceName, $className);
            }

            $servicesFileNames = [
                'Shared/services.php',
                'Shared/services.' . $codeBucket . '.php',
                $applicationType . '/services.php',
                $applicationType . '/services.' . $codeBucket . '.php'
            ];
            foreach ($servicesFileNames as $servicesFileName) {
                $servicesFilePath = APPLICATION_ROOT_DIR . '/config/' . $servicesFileName;
                if (file_exists($servicesFilePath)) {
                    $loader->load($servicesFileName);
                }
            }

            $container->compile();

            static::$symfonyContainer = $container;

            $dumper = new PhpDumper($container);
            $containerConfigCache->write(
                $dumper->dump(['class' => $cacheClassName]),
                $container->getResources()
            );

            return;
        }

        require_once $file;

        static::$symfonyContainer = new $cacheClassName();
    }
}

