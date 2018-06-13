<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcrete;

use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Pyz\Zed\DemoDataGenerator\Business\Model\AbstractGenerator;

class ProductConcreteGenerator extends AbstractGenerator implements ProductConcreteGeneratorInterface
{
    /**
     * @var array
     */
    protected $productAbstractSkus;

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductConcreteCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $this->productAbstractSkus = $this->readProductAbstractFromCsv();
        $rowsNumber = $demoDataGeneratorTransfer->getRowNumber();
        $filePath = $demoDataGeneratorTransfer->getFilePath();
        $i = 1;

        do {
            $this->createProductConcreteRow($i);
            $i++;
        } while ($i <= $rowsNumber);

        $header = array_keys($this->rows[0]);
        $this->writeCsv($header, $this->rows, $filePath);
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
        $file = $filePath ? $filePath : $this->getConfig()->getProductConcreteCsvPath();
        $this->getFileManager()->write($file, $header, $rows);
    }

    /**
     * @param int $rowNumber
     *
     * @return void
     */
    protected function createProductConcreteRow(int $rowNumber): void
    {
        $productConcreteTransfer = $this->generateProductConcrete();
        $productAbstractSkuIndex = $rowNumber - 1;
        $productAbstractSku = $this->productAbstractSkus[$productAbstractSkuIndex] ?? '';

        $row = [
            'abstract_sku' => $productAbstractSku,
            'old_sku' => $this->getOldSku($productConcreteTransfer->getSku(), $rowNumber),
            'concrete_sku' => $productConcreteTransfer->getSku(),
            'name.en_US' => '(EN) ' . $productConcreteTransfer->getLocalizedAttributes()[0]->getName(),
            'name.de_DE' => '(DE) ' . $productConcreteTransfer->getLocalizedAttributes()[0]->getName(),
        ];

        $row = array_merge($row, $this->generateAttributes());

        $row = array_merge($row, [
            'icecat_pdp_url' => '',
            'description.en_US' => '(EN) ' . $productConcreteTransfer->getLocalizedAttributes()[0]->getDescription(),
            'description.de_DE' => '(DE) ' . $productConcreteTransfer->getLocalizedAttributes()[0]->getDescription(),
            'is_searchable.en_US' => $productConcreteTransfer->getLocalizedAttributes()[0]->getIsSearchable(),
            'is_searchable.de_DE' => $productConcreteTransfer->getLocalizedAttributes()[0]->getIsSearchable(),
            'icecat_license' => '',
            'bundled' => $this->getRandomBundledProduct(),
        ]);

        $this->rows[] = $row;
    }

    /**
     * @param string $concreteSku
     * @param int $rowNumber
     *
     * @return string
     */
    protected function getOldSku($concreteSku, $rowNumber): string
    {
        $prefix = $this->getSkuPrefix($rowNumber);

        return $prefix . $rowNumber . '_' . $concreteSku;
    }

    /**
     * @param int $rowNumber
     *
     * @return string
     */
    public function getSkuPrefix($rowNumber): string
    {
        $defaultSkuPrefix = '';
        $digitsCount = strlen((string)$rowNumber);
        $skuPrefixStrategies = $this->getConfig()->getSkuPrefixStrategy();

        foreach ($skuPrefixStrategies as $skuPrefix) {
            if ($skuPrefix['digits'] === $digitsCount) {
                return $skuPrefix['prefix'];
            }
        }

        return $defaultSkuPrefix;
    }

    /**
     * @return string
     */
    protected function getRandomBundledProduct(): string
    {
        $withBundleProduct = rand(0, 1);

        if (count($this->rows) && $withBundleProduct) {
            $bundledProductCount = rand(
                $this->getConfig()->getMinProductBundleCount(),
                $this->getConfig()->getMaxProductBundleCount()
            );

            return $this->getRandomProductSku() . '/' . $bundledProductCount;
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getRandomProductSku(): string
    {
        $maxRowsIndex = max(array_keys($this->rows));
        $randomIndex = rand(0, $maxRowsIndex);

        return $this->rows[$randomIndex]['concrete_sku'];
    }

    /**
     * @return array
     */
    protected function readProductAbstractFromCsv(): array
    {
        return $this->getFileManager()->readColumn($this->getConfig()->getProductAbstractCsvPath());
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductConcrete(): ProductConcreteTransfer
    {
        return (new ProductConcreteBuilder())
            ->withLocalizedAttributes()
            ->build();
    }

    /**
     * @return array
     */
    protected function generateAttributes(): array
    {
        $attributes = [];
        $i = 0;

        do {
            $attributeIndex = $i + 1;
            $attributes = array_merge($attributes, [
                'attribute_key_' . $attributeIndex => 'att_key_' . $attributeIndex,
                'value_' . $attributeIndex => 'att_val_' . $attributeIndex,
                'attribute_key_' . $attributeIndex . '.en_US' => null,
                'value_' . $attributeIndex . '.en_US' => null,
                'attribute_key_' . $attributeIndex . '.de_DE' => null,
                'value_' . $attributeIndex . '.de_DE' => null,
            ]);
            $i++;
        } while ($i < 2);

        return $attributes;
    }
}
