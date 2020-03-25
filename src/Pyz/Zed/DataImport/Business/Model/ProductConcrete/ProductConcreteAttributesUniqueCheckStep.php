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
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;

class ProductConcreteAttributesUniqueCheckStep implements DataImportStepInterface
{
    protected const KEY_CONCRETE_SKU = 'concrete_sku';
    protected const KEY_ABSTRACT_SKU = 'abstract_sku';
    protected const KEY_ATTRIBUTES = 'attributes';

    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_ATTRIBUTES
     */
    protected const CONCRETE_PRODUCT_COL_ATTRIBUTES = 'spy_product.attributes';
    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_SKU
     */
    protected const CONCRETE_PRODUCT_COL_SKU = 'spy_product.sku';
    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap::COL_SKU
     */
    protected const ABSTRACT_PRODUCT_COL_SKU = 'spy_product_abstract.sku';

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var array
     */
    protected $concreteProductAttributesMap;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface $productRepository
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        DataImportToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->productRepository = $productRepository;
        $this->utilEncodingService = $utilEncodingService;

        $this->prepareConcreteProductAttributesMap();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSetConcreteProductSku = $dataSet[static::KEY_CONCRETE_SKU];
        $dataSetAbstractProductSku = $dataSet[static::KEY_ABSTRACT_SKU];
        $dataSetConcreteProductAttributes = $dataSet[static::KEY_ATTRIBUTES];
        ksort($dataSetConcreteProductAttributes);

        $this->checkProductConcreteAttributesUnique($dataSetAbstractProductSku, $dataSetConcreteProductSku, $dataSetConcreteProductAttributes);

        if (!isset($this->concreteProductAttributesMap[$dataSetAbstractProductSku][$dataSetConcreteProductSku])) {
            $this->concreteProductAttributesMap[$dataSetAbstractProductSku][$dataSetConcreteProductSku] = $dataSetConcreteProductAttributes;
        }
    }

    /**
     * @param string $dataSetAbstractProductSku
     * @param string $dataSetConcreteProductSku
     * @param array $dataSetConcreteProductAttributes
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function checkProductConcreteAttributesUnique(
        string $dataSetAbstractProductSku,
        string $dataSetConcreteProductSku,
        array $dataSetConcreteProductAttributes
    ): void {
        foreach ($this->concreteProductAttributesMap[$dataSetAbstractProductSku] as $concreteProductSku => $concreteProductAttributes) {
            if ($dataSetConcreteProductSku === $concreteProductSku) {
                continue;
            }

            if ($dataSetConcreteProductAttributes === $concreteProductAttributes) {
                throw new InvalidDataException(sprintf(
                    'Product concrete must have unique attributes. Attributes "%s" of sku "%s" are equal to attributes "%s" of sku "%s".',
                    $this->utilEncodingService->encodeJson($dataSetConcreteProductAttributes),
                    $dataSetConcreteProductSku,
                    $this->utilEncodingService->encodeJson($concreteProductAttributes),
                    $concreteProductSku
                ));
            }
        }
    }

    /**
     * @return void
     */
    protected function prepareConcreteProductAttributesMap(): void
    {
        $concreteProductCollection = $this->productRepository->getConcreteProductAttributes();

        foreach ($concreteProductCollection as $concreteProduct) {
            $concreteProductAttributes = $this->utilEncodingService->decodeJson($concreteProduct[static::CONCRETE_PRODUCT_COL_ATTRIBUTES], true);
            ksort($concreteProductAttributes);
            $concreteProductSku = $concreteProduct[static::CONCRETE_PRODUCT_COL_SKU];
            $abstractProductSku = $concreteProduct[static::ABSTRACT_PRODUCT_COL_SKU];

            $this->concreteProductAttributesMap[$abstractProductSku][$concreteProductSku] = $concreteProductAttributes;
        }
    }
}
