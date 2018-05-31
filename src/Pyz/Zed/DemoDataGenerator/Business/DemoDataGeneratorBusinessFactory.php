<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManager;
use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\Generator\DemoDataGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\Generator\DemoDataGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolver;
use Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolverInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\PriceProductGenerator\PriceProductGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\PriceProductGenerator\PriceProductGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractGenerator\ProductAbstractGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractGenerator\ProductAbstractGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractStoreGenerator\ProductAbstractStoreGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractStoreGenerator\ProductAbstractStoreGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcreteGenerator\ProductConcreteGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcreteGenerator\ProductConcreteGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductImageGenerator\ProductImageGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductImageGenerator\ProductImageGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\StockProductGenerator\StockProductGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\StockProductGenerator\StockProductGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\DemoDataGeneratorDependencyProvider;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig getConfig()
 */
class DemoDataGeneratorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractGenerator\ProductAbstractGeneratorInterface
     */
    public function createAbstractProductDemoDataGenerator(): ProductAbstractGeneratorInterface
    {
        return new ProductAbstractGenerator(
            $this->createFileManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcreteGenerator\ProductConcreteGeneratorInterface
     */
    public function createConcreteProductDemoDataGenerator(): ProductConcreteGeneratorInterface
    {
        return new ProductConcreteGenerator(
            $this->createFileManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\PriceProductGenerator\PriceProductGeneratorInterface
     */
    public function createPriceProductDemoDataGenerator(): PriceProductGeneratorInterface
    {
        return new PriceProductGenerator(
            $this->createFileManager(),
            $this->getConfig(),
            $this->getStore()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractStoreGenerator\ProductAbstractStoreGeneratorInterface
     */
    public function createProductAbstractStoreDemoDataGenerator(): ProductAbstractStoreGeneratorInterface
    {
        return new ProductAbstractStoreGenerator(
            $this->createFileManager(),
            $this->getConfig(),
            $this->getStore()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductImageGenerator\ProductImageGeneratorInterface
     */
    public function createProductImageDemoDataGenerator(): ProductImageGeneratorInterface
    {
        return new ProductImageGenerator(
            $this->createFileManager(),
            $this->getConfig(),
            $this->getStore()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\StockProductGenerator\StockProductGeneratorInterface
     */
    public function createStockProductDemoDataGenerator(): StockProductGeneratorInterface
    {
        return new StockProductGenerator(
            $this->createFileManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface
     */
    public function createFileManager(): FileManagerInterface
    {
        return new FileManager();
    }

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\Generator\DemoDataGeneratorInterface
     */
    public function createDemoDataGenerator(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): DemoDataGeneratorInterface
    {
        return new DemoDataGenerator(
            $demoDataGeneratorTransfer,
            $this->createProcessPluginResolver()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolverInterface
     */
    public function createProcessPluginResolver(): PluginResolverInterface
    {
        return new PluginResolver(
            $this->getDemoDataPluginsStack()
        );
    }

    /**
     * @return array
     */
    public function getDemoDataPluginsStack(): array
    {
        return $this->getProvidedDependency(DemoDataGeneratorDependencyProvider::DEMO_DATA_GENERATOR_PLUGINS);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore(): Store
    {
        return $this->getProvidedDependency(DemoDataGeneratorDependencyProvider::STORE);
    }
}
