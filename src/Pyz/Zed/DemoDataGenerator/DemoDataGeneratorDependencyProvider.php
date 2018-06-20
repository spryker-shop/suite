<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator;

use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductAbstractCsvGeneratorPlugin;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductAbstractStoreCsvGeneratorPlugin;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductConcreteCsvGeneratorPlugin;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductImageCsvGeneratorPlugin;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductPriceCsvGeneratorPlugin;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductStockCsvGeneratorPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DemoDataGeneratorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const STORE_FACADE = 'STORE_FACADE';
    public const SERVICE_UTIL_DATA_READER = 'SERVICE_UTIL_DATA_READER';
    public const DEMO_DATA_GENERATOR_PLUGINS = 'DEMO_DATA_GENERATOR_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addStoreFacade($container);
        $container = $this->addUtilDataReaderService($container);
        $container = $this->addConfigurationPluginsStack($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::STORE_FACADE] = function (Container $container) {
            return $container->getLocator()->store()->facade();
        };

        return $container;
    }

   /**
    * @param \Spryker\Zed\Kernel\Container $container
    *
    * @return \Spryker\Zed\Kernel\Container
    */
    protected function addUtilDataReaderService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurationPluginsStack($container): Container
    {
        $container[static::DEMO_DATA_GENERATOR_PLUGINS] = function () {
            return $this->getDemoDataPlugins();
        };
        return $container;
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Dependency\Plugin\DemoDataGeneratorPluginInterface[]
     */
    public function getDemoDataPlugins(): array
    {
        return [
            new ProductAbstractCsvGeneratorPlugin(),
            new ProductConcreteCsvGeneratorPlugin(),
            new ProductAbstractStoreCsvGeneratorPlugin(),
            new ProductPriceCsvGeneratorPlugin(),
            new ProductImageCsvGeneratorPlugin(),
            new ProductStockCsvGeneratorPlugin(),
        ];
    }
}
