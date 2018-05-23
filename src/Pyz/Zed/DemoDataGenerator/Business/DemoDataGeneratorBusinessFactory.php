<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcreteGenerator;

/**
 * @method \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig getConfig()
 */
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
    public function createConcreteProductDemoDataGenerator()
    {
        return new ProductConcreteGenerator();
    }
}
