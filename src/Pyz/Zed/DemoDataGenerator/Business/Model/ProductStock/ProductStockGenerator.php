<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductStock;

use Generated\Shared\DataBuilder\StockProductBuilder;
use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Pyz\Zed\DemoDataGenerator\Business\Model\AbstractGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface;
use Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig;

class ProductStockGenerator extends AbstractGenerator implements ProductStockGeneratorInterface
{
    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @param \Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface $fileManager
     * @param \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig $config
     */
    public function __construct(
        FileManagerInterface $fileManager,
        DemoDataGeneratorConfig $config
    ) {
        parent::__construct($fileManager, $config);
    }

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductStockCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $filePath = $demoDataGeneratorTransfer->getFilePath();

        $this->generateRows();

        $header = array_keys($this->rows[0]);
        $this->writeCsv($header, $this->rows, $filePath);
    }

    /**
     * @return void
     */
    protected function generateRows(): void
    {
        $productConcreteSkus = $this->readProductConcreteFromCsv();
        $stockProductTransfer = $this->generateStockProduct();

        foreach ($productConcreteSkus as $sku) {
            $stockProductTransfer->setSku($sku);
            $row = $this->createStockProductRow($stockProductTransfer);
            $this->rows[] = $row;
        }
    }

    /**
     * @return array
     */
    protected function readProductConcreteFromCsv(): array
    {
        return $this->getFileManager()->readColumn($this->getConfig()->getProductConcreteCsvPath());
    }

    /**
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    protected function generateStockProduct(): StockProductTransfer
    {
        return (new StockProductBuilder())->build();
    }

    /**
     * @return array
     */
    protected function readStockFromCsv(): array
    {
        return $this->getFileManager()->readColumn($this->getConfig()->getStockCsvPath(), 0, 0);
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return array
     */
    protected function createStockProductRow(StockProductTransfer $stockProductTransfer): array
    {
        $row = [
            'concrete_sku' => $stockProductTransfer->getSku(),
            'name' => $this->getStockName(),
            'quantity' => $stockProductTransfer->getQuantity(),
            'is_never_out_of_stock' => $stockProductTransfer->getIsNeverOutOfStock(),
            'is_bundle' => random_int(0, 1),
        ];

        return $row;
    }

    /**
     * @param array $header
     * @param array $rows
     * @param null|string $filePath
     *
     * @return void
     */
    protected function writeCsv(array $header, array $rows, ?string $filePath): void
    {
        $file = $filePath ? $filePath : $this->getConfig()->getProductStockCsvPath();
        $this->fileManager->write($file, $header, $rows);
    }

    /**
     * @return string
     */
    protected function getStockName(): string
    {
        $stockNames = $this->readStockFromCsv();

        $minIndex = min(array_keys($stockNames));
        $maxIndex = max(array_keys($stockNames));

        if ($maxIndex) {
            $randomIndex = random_int($minIndex, $maxIndex);
            return $stockNames[$randomIndex];
        }
    }
}
