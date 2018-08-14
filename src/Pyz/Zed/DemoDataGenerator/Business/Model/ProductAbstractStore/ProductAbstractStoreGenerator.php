<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstractStore;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Pyz\Zed\DemoDataGenerator\Business\Model\AbstractGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface;
use Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class ProductAbstractStoreGenerator extends AbstractGenerator implements ProductAbstractStoreGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface $fileManager
     * @param \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig $config
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct(
        FileManagerInterface $fileManager,
        DemoDataGeneratorConfig $config,
        StoreFacadeInterface $storeFacade
    ) {
        parent::__construct($fileManager, $config);
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractStoreCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $productAbstractSkus = $this->readProductAbstractFromCsv();
        $stores = $this->storeFacade->getAllStores();
        $filePath = $demoDataGeneratorTransfer->getFilePath();

        $rows = [];
        $row = [];

        foreach ($productAbstractSkus as $sku) {
            foreach ($stores as $store) {
                $row = $this->createProductAbstractStoreRow($sku, $store->getName());
                $rows[] = array_values($row);
            }
        }

        $header = array_keys($row);
        $this->writeCsv($header, $rows, $filePath);
    }

    /**
     * @return array
     */
    protected function readProductAbstractFromCsv(): array
    {
        return $this->getFileManager()->readColumn($this->getConfig()->getProductAbstractCsvPath());
    }

    /**
     * @param array $header
     * @param array $rows
     * @param string|null $filePath
     *
     * @return void
     */
    protected function writeCsv(array $header, array $rows, ?string $filePath): void
    {
        $file = $filePath ? $filePath : $this->getConfig()->getProductAbstractStoreCsvPath();
        $this->fileManager->write($file, $header, $rows);
    }

    /**
     * @param string $sku
     * @param string $store
     *
     * @return array
     */
    protected function createProductAbstractStoreRow(string $sku, string $store): array
    {
        $row = [
            'product_abstract_sku' => $sku,
            'store_name' => $store,
        ];

        return $row;
    }
}
