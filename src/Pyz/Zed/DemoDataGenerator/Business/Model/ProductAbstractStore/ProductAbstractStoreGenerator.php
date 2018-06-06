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
use Spryker\Shared\Kernel\Store;

class ProductAbstractStoreGenerator extends AbstractGenerator implements ProductAbstractStoreGeneratorInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface $fileManager
     * @param \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig $config
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        FileManagerInterface $fileManager,
        DemoDataGeneratorConfig $config,
        Store $store
    ) {
        parent::__construct($fileManager, $config);
        $this->store = $store;
    }

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractStoreCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $productAbstractSkus = $this->readProductAbstractFromCsv();
        $stores = $this->store->getAllowedStores();
        $filePath = $demoDataGeneratorTransfer->getFilePath();

        $rows = [];
        $header = [];

        foreach ($productAbstractSkus as $sku) {
            foreach ($stores as $store) {
                $row = $this->createProductAbstractStoreRow($sku, $store);
                $header = array_keys($row);
                $rows[] = array_values($row);
            }
        }

        $this->writeCsv($filePath, $header, $rows);
    }

    /**
     * @return array
     */
    protected function readProductAbstractFromCsv(): array
    {
        return $this->getFileManager()->readColumn($this->getConfig()->getProductAbstractCsvPath());
    }

    /**
     * @param string|null $filePath
     * @param array $header
     * @param array $rows
     *
     * @return void
     */
    protected function writeCsv(?string $filePath, array $header, array $rows): void
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
