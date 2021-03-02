<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ProductConfigurations\RestApi\Fixtures;

use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PyzTest\Glue\ProductConfigurations\ProductConfigurationsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class ProductConfigurationsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    public const PRODUCT_CONFIGURATION_INSTANCE_ATTRIBUTE_JSON_PATH = '.productConfigurationInstance';
    public const PRODUCT_CONFIGURATION_CART_ITEM_DATA = [
        'displayData' => '{"Preferred time of the day": "Afternoon", "Date": "9.09.2020"}',
        'configuration' => '{"time_of_day": "2"}',
        'configuratorKey' => 'installation_appointment',
        'isComplete' => false,
        'quantity' => 5,
        'availableQuantity' => 100,
        'prices' => [
            [
                'priceTypeName' => 'DEFAULT',
                'netAmount' => 23434,
                'grossAmount' => 42502,
                'currency' => [
                    'code' => 'EUR',
                    'name' => 'Euro',
                    'symbol' => '€',
                ],
                'volumePrices' => [
                    [
                        'netAmount' => 150,
                        'grossAmount' => 165,
                        'quantity' => 5,
                    ],
                    [
                        'netAmount' => 145,
                        'grossAmount' => 158,
                        'quantity' => 10,
                    ],
                    [
                        'netAmount' => 140,
                        'grossAmount' => 152,
                        'quantity' => 20,
                    ],
                ],
            ],
        ],
    ];
    public const PRODUCT_CONFIGURATION_INSTANCE_HASH = 'd3f1cc32c7cfe45608b80595e9f313c8';

    public const STORE_NAME_DE = 'DE';

    protected const TEST_USERNAME = 'UserProductConfigurationsRestApiFixtures';
    protected const TEST_PASSWORD = 'change123';

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConfigurationTransfer
     */
    protected $productConfigurationTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConfigurationTransfer
     */
    public function getProductConfigurationTransfer(): ProductConfigurationTransfer
    {
        return $this->productConfigurationTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(): OrderTransfer
    {
        return $this->orderTransfer;
    }

    /**
     * @param \PyzTest\Glue\ProductConfigurations\ProductConfigurationsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(ProductConfigurationsApiTester $I): FixturesContainerInterface
    {
        $this->createProductConcrete($I);
        $this->createProductConfiguration($I);
        $this->createCustomerTransfer($I);
        $this->createOrder($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\ProductConfigurations\ProductConfigurationsApiTester $I
     *
     * @return void
     */
    protected function createProductConcrete(ProductConfigurationsApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
    }

    /**
     * @param \PyzTest\Glue\ProductConfigurations\ProductConfigurationsApiTester $I
     *
     * @return void
     */
    protected function createProductConfiguration(ProductConfigurationsApiTester $I): void
    {
        $this->productConfigurationTransfer = $I->haveProductConfiguration([
            ProductConfigurationTransfer::FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
        ]);
    }

    /**
     * @param \PyzTest\Glue\ProductConfigurations\ProductConfigurationsApiTester $I
     *
     * @return void
     */
    protected function createCustomerTransfer(ProductConfigurationsApiTester $I): void
    {
        $this->customerTransfer = $I->haveCustomer([
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
    }

    /**
     * @param \PyzTest\Glue\ProductConfigurations\ProductConfigurationsApiTester $I
     *
     * @return void
     */
    protected function createOrder(ProductConfigurationsApiTester $I): void
    {
        $productConfigurationInstanceTransfer = $this->createProductConfigurationInstanceTransfer($this->productConfigurationTransfer);

        $this->orderTransfer = $I->haveOrderFromQuote(
            $this->createQuoteTransfer(
                $this->customerTransfer,
                $this->productConcreteTransfer,
                $productConfigurationInstanceTransfer
            ),
            $this->createStateMachine($I)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(
        CustomerTransfer $customerTransfer,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): QuoteTransfer {
        return (new QuoteBuilder())
            ->withItem([
                ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstanceTransfer->toArray(),
                ItemTransfer::SKU => $productConcreteTransfer->getSku(),
            ])
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference()])
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->withPayment()
            ->build();
    }

    /**
     * @param \PyzTest\Glue\ProductConfigurations\ProductConfigurationsApiTester $I
     *
     * @return string
     */
    protected function createStateMachine(ProductConfigurationsApiTester $I): string
    {
        $testStateMachineProcessName = 'DummyPayment01';
        $I->configureTestStateMachine([$testStateMachineProcessName]);

        return $testStateMachineProcessName;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    protected function createProductConfigurationInstanceTransfer(
        ProductConfigurationTransfer $productConfigurationTransfer
    ): ProductConfigurationInstanceTransfer {
        return (new ProductConfigurationInstanceBuilder($productConfigurationTransfer->toArray()))
            ->withPrice()
            ->build()
            ->setIsComplete(true);
    }
}
