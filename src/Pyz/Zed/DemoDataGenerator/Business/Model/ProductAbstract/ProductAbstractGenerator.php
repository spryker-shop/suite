<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstract;

use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Nette\Utils\DateTime;
use Pyz\Zed\DemoDataGenerator\Business\Model\AbstractGenerator;

class ProductAbstractGenerator extends AbstractGenerator implements ProductAbstractGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $rows = [];
        $i = 0;
        $rowsNumber = $demoDataGeneratorTransfer->getRowNumber();
        $filePath = $demoDataGeneratorTransfer->getFilePath();

        do {
            $productAbstractTransfer = $this->generateProductAbstract();
            $row = $this->createProductAbstractRow($productAbstractTransfer);
            $rows[] = array_values($row);
            $i++;
        } while ($i < $rowsNumber);

        $header = array_keys($row);
        $this->writeCsv($header, $rows, $filePath);
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
        $file = $filePath ? $filePath : $this->getConfig()->getProductAbstractCsvPath();
        $this->fileManager->write($file, $header, $rows);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductAbstract()
    {
        return (new ProductAbstractBuilder())
            ->withLocalizedAttributes()
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array
     */
    protected function createProductAbstractRow(ProductAbstractTransfer $productAbstractTransfer)
    {
        $row = [
            'category_key' => $this->getConfig()->getDefaultCategoryKey(),
            'category_product_order' => 1,
            'abstract_sku' => $productAbstractTransfer->getSku(),
            'name.en_US' => $productAbstractTransfer->getLocalizedAttributes()[0]->getName(),
            'name.de_DE' => '(DE) ' . $productAbstractTransfer->getLocalizedAttributes()[0]->getName(),
            'url.en_US' => uniqid($this->getConfig()->getDefaultUrlEn()),
            'url.de_DE' => uniqid($this->getConfig()->getDefaultUrlDe()),
            'is_featured' => 0,
        ];

        $row = array_merge($row, $this->generateAttributes());

        $row = array_merge($row, [
            'color_code' => '#ffffff',
            'description.en_US' => $productAbstractTransfer->getLocalizedAttributes()[0]->getDescription(),
            'description.de_DE' => '(DE) ' . $productAbstractTransfer->getLocalizedAttributes()[0]->getDescription(),
            'icecat_pdp_url' => null,
            'tax_set_name' => $this->getConfig()->getDefaultTaxSetName(),
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
        } while ($i < 6);

        return $attributes;
    }
}
