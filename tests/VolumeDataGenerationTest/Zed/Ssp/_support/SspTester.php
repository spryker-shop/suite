<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace VolumeDataGenerationTest\Zed\Ssp;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClass;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use VolumeDataGenerationTest\Zed\Ssp\_generated\SspTesterActions;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class SspTester extends Actor
{
    use SspTesterActions;

    /**
     * @uses VolumeDataGenerationConfig::ALL_ENTITIES_GENERATED_MESSAGE
     *
     * @var string
     */
    public const ALL_ENTITIES_GENERATED_MESSAGE = 'No more entities to process';

    /**
     * @uses VolumeDataGenerationConfig::GENERATION_RESULT_TEXT
     *
     * @var string
     */
    public const GENERATION_RESULT_TEXT = 'Generation result is:';

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyProductClass $serviceProductClassEntity
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function generateService(
        StoreTransfer $storeTransfer,
        SpyTaxSet $taxSetEntity,
        SpyProductClass $serviceProductClassEntity,
        MerchantTransfer $merchantTransfer,
    ): ProductConcreteTransfer {
        $productTransfer = $this->haveFullProduct([
            ProductAbstractTransfer::ID_TAX_SET => $taxSetEntity->getIdTaxSet(),
        ]);

        $this->haveProductToProductClass($productTransfer->getIdProductConcrete(), $serviceProductClassEntity->getIdProductClass());
        $this->haveProductImageSet([
            ProductImageSetTransfer::NAME => 'default',
            ProductImageSetTransfer::ID_PRODUCT => $productTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productTransfer->getFkProductAbstract(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageTransfer())
                    ->setExternalUrlLarge('https://images.icecat.biz/img/gallery_mediums/30691822_1486.jpg')
                    ->setExternalUrlSmall('https://images.icecat.biz/img/gallery/30691822_1486.jpg'),
            ],
        ]);

        $this->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productTransfer->getSku(),
            PriceProductTransfer::MONEY_VALUE => [
                'grossAmount' => 1000,
                'netAmount' => 1000,
            ],
        ]);

        $productOfferTransfer = $this->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => 'approved',
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
            ProductOfferTransfer::CONCRETE_SKU => $productTransfer->getSku(),
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::STORES => new ArrayObject([
                $storeTransfer,
            ]),
        ]);

        $this->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => true,
        ], [$merchantTransfer->getStocks()->getIterator()->current()->toArray()]);

        $this->haveProductInStockForStore(
            $storeTransfer,
            [
                'sku' => $productTransfer->getSku(),
                'isNeverOutOfStock' => 1,
            ],
        );

        return $productTransfer;
    }
}
