<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business;

use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcreteGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductImageGenerator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class DemoDataGeneratorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractGenerator
     */
    public function createAbstractProductDemoDataGenerator()
    {
        return new ProductAbstractGenerator();
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcreteGenerator
     */
    public function createProductConcreteDemoDataGenerator()
    {
        return new ProductConcreteGenerator();
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\ProductImageGenerator
     */
    public function createProductImageDemoDataGenerator()
    {
        return new ProductImageGenerator();
    }
}
