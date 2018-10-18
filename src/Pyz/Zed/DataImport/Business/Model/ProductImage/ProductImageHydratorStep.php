<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage;

use Generated\Shared\Transfer\SpyProductImageEntityTransfer;
use Generated\Shared\Transfer\SpyProductImageSetEntityTransfer;
use Generated\Shared\Transfer\SpyProductImageSetToProductImageEntityTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductImageHydratorStep extends PublishAwareStep implements DataImportStepInterface
{
    public const KEY_LOCALE = 'locale';
    public const KEY_IMAGE_SET_NAME = 'image_set_name';
    public const KEY_IMAGE_SET_DB_NAME_COLUMN = 'name';
    public const KEY_ABSTRACT_SKU = 'abstract_sku';
    public const KEY_CONCRETE_SKU = 'concrete_sku';
    public const KEY_EXTERNAL_URL_LARGE = 'external_url_large';
    public const KEY_EXTERNAL_URL_SMALL = 'external_url_small';
    public const KEY_IMAGE_SET_FK_PRODUCT = 'fk_product';
    public const KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET = 'id_product_image_set';
    public const KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE = 'id_product_image';
    public const KEY_IMAGE_SET_FK_RESOURCE_PRODUCT_SET = 'fk_resource_product_set';
    public const KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    public const KEY_IMAGE_SET_FK_LOCALE = 'fk_locale';
    public const KEY_SORT_ORDER = 'sort_order';
    public const IMAGE_TO_IMAGE_SET_RELATION_ORDER = 0;
    public const DATA_PRODUCT_IMAGE_SET_TRANSFER = 'DATA_PRODUCT_IMAGE_SET_TRANSFER';
    public const DATA_PRODUCT_IMAGE_TRANSFER = 'DATA_PRODUCT_IMAGE_TRANSFER';
    public const DATA_PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER = 'DATA_PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER';

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
        $imageSetEntityTransfer = new SpyProductImageSetEntityTransfer();
        $imageSetEntityTransfer->setName($dataSet[static::KEY_IMAGE_SET_NAME]);
        $imageSetEntityTransfer->setFkLocale($dataSet[static::KEY_IMAGE_SET_FK_LOCALE]);
        if (!empty($dataSet[static::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT])) {
            $imageSetEntityTransfer->setFkProductAbstract($dataSet[static::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]);
        } elseif (!empty($dataSet[static::KEY_IMAGE_SET_FK_PRODUCT])) {
            $imageSetEntityTransfer->setFkProduct($dataSet[static::KEY_IMAGE_SET_FK_PRODUCT]);
        }
        $dataSet[static::DATA_PRODUCT_IMAGE_SET_TRANSFER] = $imageSetEntityTransfer;
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

        $dataSet[static::DATA_PRODUCT_IMAGE_TRANSFER] = $imageEntityTransfer;
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

        $dataSet[static::DATA_PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER] = $imageToImageSetRelationEntityTransfer;
    }
}
