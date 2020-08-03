<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductAbstractStore;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\PropelConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductAbstractStore
 * @group ProductAbstractStoreWriterPdoTest
 * Add your own group annotations below this line
 */
class ProductAbstractStoreWriterPdoTest extends AbstractProductAbstractStoreWriterTest
{
    /**
     * @group ProductWriterTest
     * @group ProductAbstractStoreWriterTest
     * @group ProductAbstractStoreWriterPropelTest
     *
     * @return void
     */
    public function testProductAbstractStoreWriter(): void
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_PGSQL) {
            $this->markTestSkipped('PostgreSQL related test');
        }

        $writer = $this->getDataImportBusinessFactoryStub()->createProductAbstractStoreBulkPdoWriter();

        $dataSets = $this->createDataSets();
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }
        $writer->flush();

        $this->assertImportedData($dataSets, $this->queryDataFromDB(array_keys($dataSets)));
    }
}
