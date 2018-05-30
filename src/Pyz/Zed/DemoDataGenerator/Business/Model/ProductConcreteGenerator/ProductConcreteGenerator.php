<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductConcreteGenerator;

use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Pyz\Zed\DemoDataGenerator\Business\Model\AbstractGenerator;

class ProductConcreteGenerator extends AbstractGenerator implements ProductConcreteGeneratorInterface
{
    /**
     * @param int $rowsNumber
     *
     * @return void
     */
    public function createProductConcreteCsvDemoData(int $rowsNumber): void
    {
        $header = [];
        $rows = [];

        for ($i = 0; $i <= $rowsNumber; $i++) {
            $row = $this->createProductConcreteRow($i);
            $header = array_keys($row);
            $rows[] = array_values($row);
        }

        $this->writeCsv($header, $rows);
    }

    /**
     * @param array $header
     * @param array $rows
     *
     * @return void
     */
    protected function writeCsv(array $header, array $rows): void
    {
        $this->getFileManager()->write($this->getConfig()->getProductConcreteCsvPath(), $header, $rows);
    }

    /**
     * @param int $rowNumber
     *
     * @return array
     */
    protected function createProductConcreteRow(int $rowNumber): array
    {
        $productConcreteTransfer = $this->generateProductConcrete();
        $productAbstractSkus = $this->readProductAbstractFromCsv();
        $productAbstractSku = $productAbstractSkus[$rowNumber] ?? '';

        $row = [
            'abstract_sku' => $productAbstractSku,
            'old_sku' => '',
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
            'bundled' => '',
        ]);

        return $row;
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
    protected function generateProductConcrete()
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

        for ($i = 0; $i < 2; $i++) {
            $attributeIndex = $i + 1;
            $attributes = array_merge($attributes, [
                'attribute_key_' . $attributeIndex => 'att_key_' . $attributeIndex,
                'value_' . $attributeIndex => 'att_val_' . $attributeIndex,
                'attribute_key_' . $attributeIndex . '.en_US' => null,
                'value_' . $attributeIndex . '.en_US' => null,
                'attribute_key_' . $attributeIndex . '.de_DE' => null,
                'value_' . $attributeIndex . '.de_DE' => null,
            ]);
        }

        return $attributes;
    }
}
