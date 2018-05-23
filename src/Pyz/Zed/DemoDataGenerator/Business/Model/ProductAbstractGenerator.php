<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model;

use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use League\Csv\Writer;
use Nette\Utils\DateTime;

class ProductAbstractGenerator
{
    /**
     * @param int $rowsNumber
     *
     * @return void
     */
    public function createProductAbstractCsvDemoData(int $rowsNumber): void
    {
        $rows = [];
        $header = [];
        for ($i = 0; $i <= $rowsNumber; $i++) {
            $productAbstractTransfer = $this->generateProductAbstract();
            $row = $this->createProductAbstractRow($productAbstractTransfer);
            $header = array_keys($row);
            $rows[] = array_values($row);
        }

        $writer = Writer::createFromPath('data/import/icecat_biz_data/product_abstract.csv', 'w+');
        $writer->setDelimiter(',');
        $writer->insertOne($header);
        $writer->insertAll($rows);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductAbstract()
    {
        return (new ProductAbstractBuilder())->withLocalizedAttributes()->build();
    }

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array
     */
    public function createProductAbstractRow(ProductAbstractTransfer $productAbstractTransfer)
    {
        //TODO generate random category, taxSets,
        $row = [
            'category_key' => 'digital-cameras',
            'category_product_order' => 1,
            'abstract_sku' => $productAbstractTransfer->getSku(),
            'name.en_US' => $productAbstractTransfer->getLocalizedAttributes()[0]->getName(),
            'name.de_DE' => '(DE) ' . $productAbstractTransfer->getLocalizedAttributes()[0]->getName(),
            'url.en_US' => uniqid('/en/demo/url/'),
            'url.de_DE' => uniqid('/de/demo/url/'),
            'is_featured' => 0,
        ];
        $row = array_merge($row, $this->generateAttributes());
        $row = array_merge($row, [
            'color_code' => '#ffffff',
            'description.en_US' => $productAbstractTransfer->getLocalizedAttributes()[0]->getDescription(),
            'description.de_DE' => '(DE) ' . $productAbstractTransfer->getLocalizedAttributes()[0]->getDescription(),
            'icecat_pdp_url' => null,
            'tax_set_name' => 'Entertainment Electronics',
            'meta_title.en_US' => $productAbstractTransfer->getLocalizedAttributes()[0]->getMetaTitle(),
            'meta_title.de_DE' => '(DE) ' . $productAbstractTransfer->getLocalizedAttributes()[0]->getMetaTitle(),
            'meta_keywords.en_US' => $productAbstractTransfer->getLocalizedAttributes()[0]->getMetaKeywords(),
            'meta_keywords.de_DE' => '(DE) ' . $productAbstractTransfer->getLocalizedAttributes()[0]->getMetaKeywords(),
            'meta_description.en_US' => $productAbstractTransfer->getLocalizedAttributes()[0]->getMetaDescription(),
            'meta_description.de_DE' => '(DE) ' . $productAbstractTransfer->getLocalizedAttributes()[0]->getMetaDescription(),
            'icecat_license' => null,
            'new_from' => (new DateTime())->format('c'),
            'new_to' => (new DateTime())->format('c'),
        ]);

        return $row;
    }

    /**
     * @return array
     */
    protected function generateAttributes()
    {
        //TODO generate random attribute keys and values
        $attributes = [];
        for ($i = 0; $i < 6; $i++) {
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