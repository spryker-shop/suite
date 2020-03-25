<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete;

use Pyz\Zed\DataImport\Business\Exception\InvalidDataException;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductConcreteAttributeUniqueCheckStep implements DataImportStepInterface
{
    protected const KEY_CONCRETE_SKU = 'concrete_sku';
    protected const KEY_ABSTRACT_SKU = 'abstract_sku';
    protected const KEY_ATTRIBUTES = 'attributes';

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $abstractProductAttributes = $this->productRepository->getAttributesByAbstractSku($dataSet[static::KEY_ABSTRACT_SKU]);

        if (!$abstractProductAttributes) {
            return;
        }

        $this->checkProductConcreteAttributesUnique($abstractProductAttributes, $dataSet);
    }

    /**
     * @param array $abstractProductAttributes
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function checkProductConcreteAttributesUnique(array $abstractProductAttributes, DataSetInterface $dataSet): void
    {
        $dataSetConcreteSku = $dataSet[static::KEY_CONCRETE_SKU];
        $dataSetConcreteProductAttributes = $dataSet[static::KEY_ATTRIBUTES];

        foreach ($abstractProductAttributes as $concreteSku => $concreteProductAttribute) {
            if ($dataSetConcreteSku === $concreteSku) {
                continue;
            }

            if ($dataSetConcreteProductAttributes === $concreteProductAttribute) {
                throw new InvalidDataException(sprintf(
                    'Product concrete must have unique attributes. Attributes "%s" of sku "%s" is equal to attributes "%s" of sku "%s".',
                    json_encode($dataSetConcreteProductAttributes),
                    $dataSetConcreteSku,
                    json_encode($concreteProductAttribute),
                    $concreteSku
                ));
            }
        }
    }
}
