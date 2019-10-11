<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group CartsRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CartsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const TEST_USERNAME = 'test username';
    protected const TEST_PASSWORD = 'test password';

    /**
     * @var string
     */
    protected $valueForGuestCustomerReference;

    /**
     * @var string
     */
    protected $valueForGuestCustomerReferenceWithLabel;

    /**
     * @var string
     */
    protected $valueForGuestCustomerReferenceWithLabelWithEmptyCart;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransferWithLabel;

    /**
     * @var \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected $productLabelTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransferWithLabel;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransferWithLabel;

    /**
     * @return string
     */
    public function getValueForGuestCustomerReference(): string
    {
        return $this->valueForGuestCustomerReference;
    }

    /**
     * @return string
     */
    public function getValueForGuestCustomerReferenceWithLabel(): string
    {
        return $this->valueForGuestCustomerReferenceWithLabel;
    }

    /**
     * @return string
     */
    public function getValueForGuestCustomerReferenceWithLabelWithEmptyCart(): string
    {
        return $this->valueForGuestCustomerReferenceWithLabelWithEmptyCart;
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
    public function getProductConcreteTransferWithLabel(): ProductConcreteTransfer
    {
        return $this->productConcreteTransferWithLabel;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function getProductLabelTransfer(): ProductLabelTransfer
    {
        return $this->productLabelTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransfer(): QuoteTransfer
    {
        return $this->guestQuoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransferWithLabel(): QuoteTransfer
    {
        return $this->guestQuoteTransferWithLabel;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteTransfer(): QuoteTransfer
    {
        return $this->quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteTransferWithLabel(): QuoteTransfer
    {
        return $this->quoteTransferWithLabel;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CartsApiTester $I): FixturesContainerInterface
    {
        $this->productConcreteTransfer = $this->createProduct($I);
        $this->productConcreteTransferWithLabel = $this->createProduct($I);

        $this->productLabelTransfer = $this->createProductLabel($I);
        $this->assignLabelToProduct($I, $this->productLabelTransfer, $this->productConcreteTransferWithLabel);

        $this->assignProductUpSelling($I, $this->productConcreteTransfer, $this->productConcreteTransferWithLabel);
        $this->assignProductUpSelling($I, $this->productConcreteTransferWithLabel, $this->productConcreteTransfer);

        $this->valueForGuestCustomerReference = $this->createValueForGuestCustomerReference();
        $this->valueForGuestCustomerReferenceWithLabel = $this->createValueForGuestCustomerReference();
        $this->valueForGuestCustomerReferenceWithLabelWithEmptyCart = $this->createValueForGuestCustomerReference();

        $this->customerTransfer = $this->createCustomer($I);
        $guestCustomerTransfer = (new CustomerTransfer())
            ->setCustomerReference($I::ANONYMOUS_PREFIX . $this->valueForGuestCustomerReference);
        $guestCustomerTransferWithLabel = (new CustomerTransfer())
            ->setCustomerReference($I::ANONYMOUS_PREFIX . $this->valueForGuestCustomerReferenceWithLabel);

        $this->guestQuoteTransfer = $this->createQuote($I, $guestCustomerTransfer, [$this->productConcreteTransfer]);
        $this->quoteTransfer = $this->createQuote($I, $this->customerTransfer, [$this->productConcreteTransfer]);
        $this->guestQuoteTransferWithLabel = $this->createQuote($I, $guestCustomerTransferWithLabel, [$this->productConcreteTransferWithLabel]);
        $this->quoteTransferWithLabel = $this->createQuote($I, $this->customerTransfer, [$this->productConcreteTransferWithLabel]);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProduct(CartsApiTester $I): ProductConcreteTransfer
    {
        return $I->haveFullProduct();
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function createProductLabel(CartsApiTester $I): ProductLabelTransfer
    {
        return $I->haveProductLabel();
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomer(CartsApiTester $I): CustomerTransfer
    {
        return $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote(CartsApiTester $I, CustomerTransfer $customerTransfer, array $productConcreteTransfers): QuoteTransfer
    {
        return $I->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::TOTALS => (new TotalsTransfer())->setPriceToPay(random_int(1000, 10000)),
            QuoteTransfer::ITEMS => $this->mapProductConcreteTransfersToQuoteTransferItems($productConcreteTransfers),
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return array
     */
    protected function mapProductConcreteTransfersToQuoteTransferItems(array $productConcreteTransfers): array
    {
        $quoteTransferItems = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $quoteTransferItems[] = [
                ItemTransfer::SKU => $productConcreteTransfer->getSku(),
                ItemTransfer::GROUP_KEY => $productConcreteTransfer->getSku(),
                ItemTransfer::ABSTRACT_SKU => $productConcreteTransfer->getAbstractSku(),
                ItemTransfer::ID => $productConcreteTransfer->getIdProductConcrete(),
                ItemTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                ItemTransfer::UNIT_PRICE => random_int(100, 1000),
                ItemTransfer::QUANTITY => 5,
            ];
        }

        return $quoteTransferItems;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assignLabelToProduct(
        CartsApiTester $I,
        ProductLabelTransfer $productLabelTransfer,
        ProductConcreteTransfer $productConcreteTransfer
    ): void {
        $I->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract()
        );
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransferRelated
     *
     * @return void
     */
    protected function assignProductUpSelling(
        CartsApiTester $I,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcreteTransfer $productConcreteTransferRelated
    ): void {
        $I->haveProductRelation(
            $productConcreteTransferRelated->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract()
        );
    }

    /**
     * @return string
     */
    protected function createValueForGuestCustomerReference(): string
    {
        return uniqid('testReference', true);
    }
}
