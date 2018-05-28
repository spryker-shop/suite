<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use League\Csv\Writer;

class ProductConcreteGenerator extends AbstractDataGenerator
{
    /**
     * @param int $rowsNumber
     *
     * @return void
     */
    public function createProductConcreteCsvDemoData(int $rowsNumber): void
    {
        $rows = [];
        $header = [];

        for ($i = 0; $i <= $rowsNumber; $i++) {
            $productConcreteTransfer = $this->generateProductConcrete();
            $productAbstractTransfer = $this->generateProductAbstract();
            $row = $this->createProductConcreteRow($productConcreteTransfer, $productAbstractTransfer);
            $header = array_keys($row);
            $rows[] = array_values($row);
        }

        $this->writeData($header, $rows);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array
     */
    public function createProductConcreteRow(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ) {
        $row = [
            'abstract_sku' => $productAbstractTransfer->getSku(),
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
     * @param array $header
     * @param array $rows
     *
     * @return void
     */
    protected function writeData(array $header, array $rows)
    {
        $writer = Writer::createFromPath('data/import/icecat_biz_data/product_concrete.csv', 'w+');
        $writer->setDelimiter(',');
        $writer->insertOne($header);
        $writer->insertAll($rows);
    }

    /**
     * @return array
     */
    protected function generateAttributes()
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
