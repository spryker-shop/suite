<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator;

use Pyz\Zed\DemoDataGenerator\Communication\Plugin\PriceProductCsvGeneratorPlugin;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductAbstractCsvGeneratorPlugin;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductAbstractStoreCsvGeneratorPlugin;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductConcreteCsvGeneratorPlugin;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DemoDataGeneratorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const STORE = 'STORE';
    public const SERVICE_UTIL_DATA_READER = 'SERVICE_UTIL_DATA_READER';
    public const DEMO_DATA_GENERATOR_PLUGINS = 'DEMO_DATA_GENERATOR_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addUtilDataReaderService($container);
        $container = $this->addConfigurationPluginsStack($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
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
            return $this->demoDataPluginStack();
        };
        return $container;
    }

    /**
     * @return array
     */
    public function demoDataPluginStack(): array
    {
        return [
            new ProductAbstractCsvGeneratorPlugin(),
            new ProductConcreteCsvGeneratorPlugin(),
            new ProductAbstractStoreCsvGeneratorPlugin(),
            new PriceProductCsvGeneratorPlugin(),
        ];
    }
}
