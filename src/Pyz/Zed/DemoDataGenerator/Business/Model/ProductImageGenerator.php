<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model;

use League\Csv\Writer;

class ProductImageGenerator extends AbstractDataGenerator
{
    const PRODUCT_CONCRETE_TRANSFER = 'PRODUCT_CONCRETE_TRANSFER';
    const PRODUCT_ABSTRACT_TRANSFER = 'PRODUCT_ABSTRACT_TRANSFER';
    const PRODUCT_IMAGE_TRANSFER = 'PRODUCT_IMAGE_TRANSFER';
    const PRODUCT_IMAGE_SET_TRANSFER = 'PRODUCT_IMAGE_SET_TRANSFER';
    const LOCALE_TRANSFER = 'LOCALE_TRANSFER';

    /**
     * @param int $rowsNumber
     *
     * @return void
     */
    public function createProductImageCsvDemoData(int $rowsNumber): void
    {
        $rows = [];
        $header = [];
        $productImageRowTransfers = [];

        for ($i = 0; $i <= $rowsNumber; $i++) {
            $productImageRowTransfers[static::PRODUCT_IMAGE_TRANSFER] = $this->generateProductImage();
            $productImageRowTransfers[static::PRODUCT_CONCRETE_TRANSFER] = $this->generateProductConcrete();
            $productImageRowTransfers[static::PRODUCT_ABSTRACT_TRANSFER] = $this->generateProductAbstract();
            $productImageRowTransfers[static::PRODUCT_IMAGE_SET_TRANSFER] = $this->generateProductImageSet();
            $productImageRowTransfers[static::LOCALE_TRANSFER] = $this->generateLocale();

            $row = $this->createProductImageRow($productImageRowTransfers);
            $header = array_keys($row);
            $rows[] = array_values($row);
        }

        $this->writeData($header, $rows);
    }

    /**
     * @param array $productImageRowTransfers
     *
     * @return array
     */
    public function createProductImageRow($productImageRowTransfers)
    {
        $row = [
            'image_set_name' => $productImageRowTransfers[static::PRODUCT_IMAGE_SET_TRANSFER]->getName(),
            'external_url_large' => $productImageRowTransfers[static::PRODUCT_IMAGE_TRANSFER]->getExternalUrlLarge(),
            'external_url_small' => $productImageRowTransfers[static::PRODUCT_IMAGE_TRANSFER]->getExternalUrlSmall(),
            'locale' => $productImageRowTransfers[static::LOCALE_TRANSFER]->getLocaleName(),
            'abstract_sku' => $productImageRowTransfers[static::PRODUCT_ABSTRACT_TRANSFER]->getSku(),
            'concrete_sku' => $productImageRowTransfers[static::PRODUCT_CONCRETE_TRANSFER]->getSku(),
        ];

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
        $writer = Writer::createFromPath('data/import/icecat_biz_data/product_image.csv', 'w+');
        $writer->setDelimiter(',');
        $writer->insertOne($header);
        $writer->insertAll($rows);
    }
}
