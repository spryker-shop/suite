<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CartReorder\RestApi\Fixtures;

use ArrayObject;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PyzTest\Glue\CartReorder\CartReorderApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class MerchantProductOfferCartReorderRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var string
     */
    protected const TEST_USERNAME = 'MerchantProductOfferCartReorderRestApiFixtures';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected CustomerTransfer $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\MerchantTransfer
     */
    protected MerchantTransfer $merchantTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected ProductConcreteTransfer $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected ProductConcreteTransfer $productConcreteTransferWithMerchantProductOffer;

    /**
     * @var \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected ProductOfferTransfer $productOfferTransfer;

    /**
     * @var \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected SaveOrderTransfer $orderWithMerchantProductOffer;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantTransfer(): MerchantTransfer
    {
        return $this->merchantTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransferWithMerchantProductOffer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransferWithMerchantProductOffer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function getProductOfferTransfer(): ProductOfferTransfer
    {
        return $this->productOfferTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getOrderWithMerchantProductOffer(): SaveOrderTransfer
    {
        return $this->orderWithMerchantProductOffer;
    }

    /**
     * @param \PyzTest\Glue\CartReorder\CartReorderApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CartReorderApiTester $I): FixturesContainerInterface
    {
        $I->configureStateMachine();
        $this->storeTransfer = $I->getCurrentStore();
        $this->customerTransfer = $I->createCustomer(static::TEST_USERNAME);

        $this->merchantTransfer = $this->createMerchant($I);
        $this->productConcreteTransfer = $I->createProductWithPriceAndStock($this->storeTransfer);
        $this->productConcreteTransferWithMerchantProductOffer = $I->createProductWithPriceAndStock($this->storeTransfer);
        $this->productOfferTransfer = $this->createProductOffer($I);

        $this->orderWithMerchantProductOffer = $this->createOrderWithMerchantProductOffer($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\CartReorder\CartReorderApiTester $I
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function createMerchant(CartReorderApiTester $I): MerchantTransfer
    {
        $merchantTransfer = (new MerchantBuilder([
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED,
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STORE_RELATION => (new StoreRelationTransfer())
                ->addIdStores($this->storeTransfer->getIdStoreOrFail())
                ->addStores($this->storeTransfer),
        ]))->withMerchantProfile()->build();

        $merchantTransfer = $I->haveMerchant($merchantTransfer->toArray(true, true));
        foreach ($merchantTransfer->getStocks() as $stockTransfer) {
            $I->haveStockStoreRelation($stockTransfer, $this->storeTransfer);
        }

        return $merchantTransfer;
    }

    /**
     * @param \PyzTest\Glue\CartReorder\CartReorderApiTester $I
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function createProductOffer(CartReorderApiTester $I): ProductOfferTransfer
    {
        $productOfferTransfer = $I->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $this->productConcreteTransferWithMerchantProductOffer->getIdProductConcreteOrFail(),
            ProductOfferTransfer::CONCRETE_SKU => $this->productConcreteTransferWithMerchantProductOffer->getSkuOrFail(),
            ProductOfferTransfer::MERCHANT_REFERENCE => $this->merchantTransfer->getMerchantReferenceOrFail(),
            ProductOfferTransfer::STORES => new ArrayObject([$this->storeTransfer]),
        ]);

        $merchantStocksData = array_map(function (StockTransfer $stockTransfer) {
            return $stockTransfer->toArray();
        }, $this->merchantTransfer->getStocks()->getArrayCopy());

        $I->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferStockTransfer::QUANTITY => 100,
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => true,
        ], $merchantStocksData);

        return $productOfferTransfer;
    }

    /**
     * @param \PyzTest\Glue\CartReorder\CartReorderApiTester $I
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createOrderWithMerchantProductOffer(CartReorderApiTester $I): SaveOrderTransfer
    {
        $itemsData = [
            (new ItemBuilder([
                ItemTransfer::SKU => $this->productConcreteTransfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 1,
            ]))->build()->toArray(),
            (new ItemBuilder([
                ItemTransfer::SKU => $this->productConcreteTransferWithMerchantProductOffer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 2,
                ItemTransfer::PRODUCT_OFFER_REFERENCE => $this->productOfferTransfer->getProductOfferReferenceOrFail(),
                ItemTransfer::MERCHANT_REFERENCE => $this->merchantTransfer->getMerchantReferenceOrFail(),
            ]))->build()->toArray(),
        ];

        return $I->createOrder($this->customerTransfer, [QuoteTransfer::ITEMS => $itemsData]);
    }
}
