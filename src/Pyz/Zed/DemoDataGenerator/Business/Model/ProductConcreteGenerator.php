<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model;

use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use League\Csv\Writer;
use Nette\Utils\DateTime;

class ProductConcreteGenerator
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
            $ProductConcreteTransfer = $this->generateProductConcrete();
            $row = $this->createProductConcreteRow($ProductConcreteTransfer);
            $header = array_keys($row);
            $rows[] = array_values($row);
        }

        $writer = Writer::createFromPath('data/import/icecat_biz_data/product_concrete.csv', 'w+');
        $writer->setDelimiter(',');
        $writer->insertOne($header);
        $writer->insertAll($rows);
    }

    /**
     * @param ProductConcreteTransfer $ProductConcreteTransfer
     *
     * @return array
     */
    public function createProductConcreteRow(ProductConcreteTransfer $ProductConcreteTransfer)
    {
        $row = [
            'abstract_sku' => '001',//$ProductConcreteTransfer->getAbstractSku(),
            'old_sku' => '',
            'concrete_sku' => $ProductConcreteTransfer->getSku(),
            'name.en_US' => '(EN) '. $ProductConcreteTransfer->getLocalizedAttributes()[0]->getName(),
            'name.de_DE' => '(DE) ' . $ProductConcreteTransfer->getLocalizedAttributes()[0]->getName(),
        ];
        $row = array_merge($row, $this->generateAttributes());
        $row = array_merge($row, [
            'icecat_pdp_url' => null,
            'description.en_US' => '(DE) ' . $ProductConcreteTransfer->getLocalizedAttributes()[0]->getDescription(),
            'description.de_DE' => '(DE) ' . $ProductConcreteTransfer->getLocalizedAttributes()[0]->getDescription(),
            'is_searchable.en_US' => 1,//$ProductConcreteTransfer->getLocalizedAttributes()[0]->getIsSearchable(),
            'is_searchable.de_DE' => 1,//$ProductConcreteTransfer->getLocalizedAttributes()[0]->getIsSearchable(),
            'icecat_license' => null,
            'bundled' => $ProductConcreteTransfer->getProductBundle(),
        ]);

        return $row;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductConcrete()
    {
        return (new ProductConcreteBuilder())->withLocalizedAttributes()->build();
    }

    /**
     * @return array
     */
    protected function generateAttributes()
    {
        //TODO generate random attribute keys and values
        $attributes = [];
        for ($i = 0; $i < 2; $i++) {
            $attributeIndex = $i + 1;
            $attributes = array_merge($attributes, [
                'attribute_key_'. $attributeIndex => 'att_key_' . $attributeIndex,
                'value_' . $attributeIndex => 'att_val_' . $attributeIndex,
                'attribute_key_'.$attributeIndex.'.en_US' => null,
                'value_'.$attributeIndex.'.en_US' => null,
                'attribute_key_'.$attributeIndex.'.de_DE' => null,
                'value_'.$attributeIndex.'.de_DE' => null,
            ]);
        }

        return $attributes;
    }
}