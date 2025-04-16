<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\OrderAmendments\RestApi\Fixtures;

use ArrayObject;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class ProductOfferPriceOrderAmendmentRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var int
     */
    public const DEFAULT_UNIT_PRICE_AMOUNT = 10000;

    /**
     * @var int
     */
    public const BIGGER_UNIT_PRICE_AMOUNT = 15000;

    /**
     * @var int
     */
    public const LOWER_UNIT_PRICE_AMOUNT = 5000;

    /**
     * @var string
     */
    protected const TEST_USERNAME = 'ProductOfferPriceOrderAmendmentRestApiFixtures';

    /**
     * @var string
     */
    protected const TEST_PASSWORD = 'change123';

    /**
     * @var string
     */
    protected const STATE_MACHINE_NAME = 'DummyPayment01';

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_GRACE_PERIOD_STARTED = 'grace period started';

    /**
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected CustomerTransfer $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected SaveOrderTransfer $readyForAmendmentOrderTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected ProductConcreteTransfer $productWithBiggerPrice;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected ProductConcreteTransfer $productWithLowerPrice;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

    /**
     * @var \Generated\Shared\Transfer\MerchantTransfer
     */
    protected MerchantTransfer $merchantTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected ProductOfferTransfer $productOfferWithBiggerPrice;

    /**
     * @var \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected ProductOfferTransfer $productOfferWithLowerPrice;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getReadyForAmendmentOrderTransfer(): SaveOrderTransfer
    {
        return $this->readyForAmendmentOrderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductWithBiggerPrice(): ProductConcreteTransfer
    {
        return $this->productWithBiggerPrice;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductWithLowerPrice(): ProductConcreteTransfer
    {
        return $this->productWithLowerPrice;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransfer(): StoreTransfer
    {
        return $this->storeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantTransfer(): MerchantTransfer
    {
        return $this->merchantTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function getProductOfferWithBiggerPrice(): ProductOfferTransfer
    {
        return $this->productOfferWithBiggerPrice;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function getProductOfferWithLowerPrice(): ProductOfferTransfer
    {
        return $this->productOfferWithLowerPrice;
    }

    /**
     * @param \PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(OrderAmendmentsApiTester $I): FixturesContainerInterface
    {
        $this->configureStateMachine($I);
        $this->customerTransfer = $this->createCustomerTransfer($I);

        $this->storeTransfer = $I->getCurrentStore();
        $this->merchantTransfer = $this->createMerchant($I);

        $this->productWithBiggerPrice = $I->haveProductWithPriceAndStock(static::DEFAULT_UNIT_PRICE_AMOUNT);
        $this->productWithLowerPrice = $I->haveProductWithPriceAndStock(static::DEFAULT_UNIT_PRICE_AMOUNT);
        $this->productOfferWithBiggerPrice = $this->createProductOffer($I, $this->productWithBiggerPrice);
        $this->productOfferWithLowerPrice = $this->createProductOffer($I, $this->productWithLowerPrice);

        $this->readyForAmendmentOrderTransfer = $this->createOrderWithProductOffers($I);
        $this->setOrderItemsState(
            $I,
            $this->readyForAmendmentOrderTransfer->getOrderItems(),
            static::ORDER_ITEM_STATE_GRACE_PERIOD_STARTED,
        );

        $I->updatePriceProductStore(
            $this->productWithBiggerPrice->getPrices()[0]->getIdPriceProduct(),
            static::BIGGER_UNIT_PRICE_AMOUNT,
            static::BIGGER_UNIT_PRICE_AMOUNT,
        );

        $I->updatePriceProductStore(
            $this->productWithLowerPrice->getPrices()[0]->getIdPriceProduct(),
            static::LOWER_UNIT_PRICE_AMOUNT,
            static::LOWER_UNIT_PRICE_AMOUNT,
        );

        return $this;
    }

    /**
     * @param \PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(OrderAmendmentsApiTester $I): CustomerTransfer
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);

        return $I->confirmCustomer($customerTransfer);
    }

    /**
     * @param \PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester $I
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createOrderWithProductOffers(OrderAmendmentsApiTester $I): SaveOrderTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $this->customerTransfer->getCustomerReference()])
            ->withItem([
                ItemTransfer::SKU => $this->productWithBiggerPrice->getSkuOrFail(),
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::PRODUCT_OFFER_REFERENCE => $this->productOfferWithBiggerPrice->getProductOfferReferenceOrFail(),
                ItemTransfer::MERCHANT_REFERENCE => $this->merchantTransfer->getMerchantReferenceOrFail(),
                ItemTransfer::UNIT_PRICE => static::DEFAULT_UNIT_PRICE_AMOUNT,
                ItemTransfer::UNIT_GROSS_PRICE => static::DEFAULT_UNIT_PRICE_AMOUNT,
            ])
            ->withItem([
                ItemTransfer::SKU => $this->productWithLowerPrice->getSkuOrFail(),
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::PRODUCT_OFFER_REFERENCE => $this->productOfferWithLowerPrice->getProductOfferReferenceOrFail(),
                ItemTransfer::MERCHANT_REFERENCE => $this->merchantTransfer->getMerchantReferenceOrFail(),
                ItemTransfer::UNIT_PRICE => static::DEFAULT_UNIT_PRICE_AMOUNT,
                ItemTransfer::UNIT_GROSS_PRICE => static::DEFAULT_UNIT_PRICE_AMOUNT,
            ])
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->withPayment()
            ->build();

        $quoteTransfer->setPriceMode(static::PRICE_MODE_GROSS);

        return $I->haveOrderFromQuote($quoteTransfer, static::STATE_MACHINE_NAME);
    }

    /**
     * @param \PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester $I
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param string $stateName
     *
     * @return void
     */
    protected function setOrderItemsState(OrderAmendmentsApiTester $I, ArrayObject $itemTransfers, string $stateName): void
    {
        foreach ($itemTransfers as $itemTransfer) {
            $I->setItemState($itemTransfer->getIdSalesOrderItemOrFail(), $stateName);
        }
    }

    /**
     * @param \PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester $I
     *
     * @return void
     */
    protected function configureStateMachine(OrderAmendmentsApiTester $I): void
    {
        $I->configureTestStateMachine([static::STATE_MACHINE_NAME]);
    }

    /**
     * @param \PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester $I
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function createProductOffer(
        OrderAmendmentsApiTester $I,
        ProductConcreteTransfer $productConcreteTransfer,
    ): ProductOfferTransfer {
        $productOfferTransfer = $I->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcreteOrFail(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSkuOrFail(),
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
     * @param \PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester $I
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function createMerchant(OrderAmendmentsApiTester $I): MerchantTransfer
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
}
