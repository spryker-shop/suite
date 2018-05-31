<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage;

use Generated\Shared\Transfer\SpyProductImageEntityTransfer;
use Generated\Shared\Transfer\SpyProductImageSetEntityTransfer;
use Generated\Shared\Transfer\SpyProductImageSetToProductImageEntityTransfer;
use Pyz\Zed\DataImport\Business\Model\Locale\Repository\LocaleRepositoryInterface;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductImageHydratorStep extends PublishAwareStep implements DataImportStepInterface
{
    const KEY_LOCALE = 'locale';
    const KEY_IMAGE_SET_NAME = 'image_set_name';
    const KEY_IMAGE_SET_DB_NAME_COLUMN = 'name';
    const KEY_ABSTRACT_SKU = 'abstract_sku';
    const KEY_CONCRETE_SKU = 'concrete_sku';
    const KEY_EXTERNAL_URL_LARGE = 'external_url_large';
    const KEY_EXTERNAL_URL_SMALL = 'external_url_small';
    const KEY_IMAGE_SET_FK_PRODUCT = 'fk_product';
    const KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET = 'id_product_image_set';
    const KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE = 'id_product_image';
    const KEY_IMAGE_SET_FK_RESOURCE_PRODUCT_SET = 'fk_resource_product_set';
    const KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    const KEY_IMAGE_SET_FK_LOCALE = 'fk_locale';
    const KEY_SORT_ORDER = 'sort_order';
    const IMAGE_TO_IMAGE_SET_RELATION_ORDER = 0;
    const PRODUCT_IMAGE_SET_TRANSFER = 'PRODUCT_IMAGE_SET_TRANSFER';
    const PRODUCT_IMAGE_TRANSFER = 'PRODUCT_IMAGE_TRANSFER';
    const PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER = 'PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER';

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Locale\Repository\LocaleRepositoryInterface
     */
    protected $localeRepository;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Locale\Repository\LocaleRepositoryInterface $localeRepository
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface $productRepository
     */
    public function __construct(LocaleRepositoryInterface $localeRepository, ProductRepositoryInterface $productRepository)
    {
        $this->localeRepository = $localeRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->importImageSet($dataSet);
        $this->importImage($dataSet);
        $this->importImageToImageSetRelation($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importImageSet(DataSetInterface $dataSet): void
    {
        $idLocale = $this->getIdLocaleByLocale($dataSet);

        $imageSetEntityTransfer = new SpyProductImageSetEntityTransfer();
        $imageSetEntityTransfer->setName($dataSet[static::KEY_IMAGE_SET_NAME]);
        $imageSetEntityTransfer->setFkLocale($idLocale);

        if (!empty($dataSet[static::KEY_ABSTRACT_SKU])) {
            $idProductAbstract = $this->productRepository->getIdProductAbstractByAbstractSku($dataSet[static::KEY_ABSTRACT_SKU]);
            $imageSetEntityTransfer->setFkProductAbstract($idProductAbstract);
            $imageSetEntityTransfer->setFkProduct('null');
        }

        if (!empty($dataSet[static::KEY_CONCRETE_SKU])) {
            $idProduct = $this->productRepository->getIdProductByConcreteSku($dataSet[static::KEY_CONCRETE_SKU]);
            $imageSetEntityTransfer->setFkProduct($idProduct);
            $imageSetEntityTransfer->setFkProductAbstract('null');
        }

        $dataSet[static::PRODUCT_IMAGE_SET_TRANSFER] = $imageSetEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importImage(DataSetInterface $dataSet): void
    {
        $imageEntityTransfer = new SpyProductImageEntityTransfer();
        $imageEntityTransfer->setExternalUrlLarge($dataSet[static::KEY_EXTERNAL_URL_LARGE]);
        $imageEntityTransfer->setExternalUrlSmall($dataSet[static::KEY_EXTERNAL_URL_SMALL]);

        $dataSet[static::PRODUCT_IMAGE_TRANSFER] = $imageEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importImageToImageSetRelation(DataSetInterface $dataSet): void
    {
        $imageToImageSetRelationEntityTransfer = new SpyProductImageSetToProductImageEntityTransfer();
        $imageToImageSetRelationEntityTransfer->setSortOrder(static::IMAGE_TO_IMAGE_SET_RELATION_ORDER);

        $dataSet[static::PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER] = $imageToImageSetRelationEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    protected function getIdLocaleByLocale(DataSetInterface $dataSet): int
    {
        $idLocale = null;

        if (!empty($dataSet[static::KEY_LOCALE])) {
            $idLocale = $this->localeRepository->getIdLocaleByLocale($dataSet[static::KEY_LOCALE]);
        }

        return $idLocale;
    }
}
