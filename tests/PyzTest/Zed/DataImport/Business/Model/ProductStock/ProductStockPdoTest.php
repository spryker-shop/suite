<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductStock;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductStock
 * @group ProductStockPdoTest
 * Add your own group annotations below this line
 */
class ProductStockPdoTest extends AbstractProductStockWriterTest
{
    /**
     * @group ProductWriterTest
     * @group ProductStockWriterTest
     * @group ProductStockWriterPropelTest
     *
     * @return void
     */
    public function testProductStockWriter(): void
    {
        // This test is randomly failed, this should be fixed with followup ticket for https://spryker.atlassian.net/browse/CC-116
//        $this->markTestSkipped(true);
        $writer = $this->getDataImportBusinessFactoryStub()->createProductStockBulkPdoWriter();

        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== Config::get(PropelConstants::ZED_DB_ENGINE_PGSQL)) {
            $this->markTestSkipped('PostgreSQL related test');
        }

        $productSkus = $this->getProductsSkus();
        $warehouses = $this->getWarehouses();

        $dataSets = $this->createDataSets($productSkus, $warehouses);
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }
        $writer->flush();

        $this->assertImportedData($dataSets, $this->queryDataFromDB($productSkus, $warehouses));
    }
}
