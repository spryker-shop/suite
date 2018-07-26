<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business;

use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManager;
use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\Generator\DemoDataGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\Generator\DemoDataGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolver;
use Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolverInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstract\ProductAbstractGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstract\ProductAbstractGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractStore\ProductAbstractStoreGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractStore\ProductAbstractStoreGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcrete\ProductConcreteGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcrete\ProductConcreteGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductImage\ProductImageGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductImage\ProductImageGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductPrice\ProductPriceGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductPrice\ProductPriceGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductStock\ProductStockGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductStock\ProductStockGeneratorInterface;
use Pyz\Zed\DemoDataGenerator\DemoDataGeneratorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * @method \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig getConfig()
 */
class DemoDataGeneratorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstract\ProductAbstractGeneratorInterface
     */
    public function createAbstractProductDemoDataGenerator(): ProductAbstractGeneratorInterface
    {
        return new ProductAbstractGenerator(
            $this->createFileManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcrete\ProductConcreteGeneratorInterface
     */
    public function createConcreteProductDemoDataGenerator(): ProductConcreteGeneratorInterface
    {
        return new ProductConcreteGenerator(
            $this->createFileManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductPrice\ProductPriceGeneratorInterface
     */
    public function createProductPriceDemoDataGenerator(): ProductPriceGeneratorInterface
    {
        return new ProductPriceGenerator(
            $this->createFileManager(),
            $this->getConfig(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractStore\ProductAbstractStoreGeneratorInterface
     */
    public function createProductAbstractStoreDemoDataGenerator(): ProductAbstractStoreGeneratorInterface
    {
        return new ProductAbstractStoreGenerator(
            $this->createFileManager(),
            $this->getConfig(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductImage\ProductImageGeneratorInterface
     */
    public function createProductImageDemoDataGenerator(): ProductImageGeneratorInterface
    {
        return new ProductImageGenerator(
            $this->createFileManager(),
            $this->getConfig(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductStock\ProductStockGeneratorInterface
     */
    public function createProductStockDemoDataGenerator(): ProductStockGeneratorInterface
    {
        return new ProductStockGenerator(
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
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\Generator\DemoDataGeneratorInterface
     */
    public function createDemoDataGenerator(): DemoDataGeneratorInterface
    {
        return new DemoDataGenerator();
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
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(DemoDataGeneratorDependencyProvider::STORE_FACADE);
    }
}
