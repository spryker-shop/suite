<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorBusinessFactory getFactory()
 */
class DemoDataGeneratorFacade extends AbstractFacade implements DemoDataGeneratorFacadeInterface
{
    /**
     * @param int $rowsNumber
     *
     * @return void
     */
    public function createProductAbstractCsvDemoData(int $rowsNumber): void
    {
        $this->getFactory()->createAbstractProductDemoDataGenerator()->createProductAbstractCsvDemoData($rowsNumber);
    }

    /**
     * @param int $rowsNumber
     *
     * @return void
     */
    public function createProductConcreteCsvDemoData(int $rowsNumber): void
    {
        $this->getFactory()->createConcreteProductDemoDataGenerator()->createProductConcreteCsvDemoData($rowsNumber);
    }
}
