<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout;

use Codeception\Util\HttpCode;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\TotalsBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Generated\Shared\Transfer\RestPaymentTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Shared\Price\PriceConfig;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class CheckoutApiTester extends ApiEndToEndTester
{
    use _generated\CheckoutApiTesterActions;

    protected const REQUEST_PARAM_PAYMENT_METHOD_NAME_INVOICE = 'invoice';
    protected const REQUEST_PARAM_PAYMENT_PROVIDER_NAME_DUMMY_PAYMENT = 'DummyPayment';
    protected const REQUEST_PARAM_ID_SHIPMENT_METHOD_DEFAULT = 1;

    /**
     * @param string[] $includes
     *
     * @return string
     */
    public function buildCheckoutUrl(array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceCarts}' . $this->formatQueryInclude($includes),
            [
                'resourceCarts' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
            ]
        );
    }

    /**
     * @param string[] $includes
     *
     * @return string
     */
    public function buildCheckoutDataUrl(array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceCheckoutData}' . $this->formatQueryInclude($includes),
            [
                'resourceCheckoutData' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
            ]
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()
            ->store()
            ->facade();
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getLocator()
            ->customer()
            ->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return array
     */
    public function getAddressRequestParams(AddressTransfer $addressTransfer): array
    {
        return [
            AddressTransfer::SALUTATION => $addressTransfer->getSalutation(),
            AddressTransfer::FIRST_NAME => $addressTransfer->getFirstName(),
            AddressTransfer::LAST_NAME => $addressTransfer->getLastName(),
            AddressTransfer::ADDRESS1 => $addressTransfer->getAddress1(),
            AddressTransfer::ADDRESS2 => $addressTransfer->getAddress2(),
            AddressTransfer::ADDRESS3 => $addressTransfer->getAddress3(),
            AddressTransfer::ZIP_CODE => $addressTransfer->getZipCode(),
            AddressTransfer::CITY => $addressTransfer->getCity(),
            AddressTransfer::ISO2_CODE => $addressTransfer->getIso2Code(),
            AddressTransfer::PHONE => $addressTransfer->getPhone(),
            AddressTransfer::EMAIL => $addressTransfer->getEmail(),
            AddressTransfer::IS_DEFAULT_BILLING => $addressTransfer->getIsDefaultBilling(),
            AddressTransfer::IS_DEFAULT_SHIPPING => $addressTransfer->getIsDefaultShipping(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    public function getCustomerRequestParams(CustomerTransfer $customerTransfer): array
    {
        return [
            CustomerTransfer::SALUTATION => $customerTransfer->getSalutation(),
            CustomerTransfer::FIRST_NAME => $customerTransfer->getFirstName(),
            CustomerTransfer::LAST_NAME => $customerTransfer->getLastName(),
            CustomerTransfer::EMAIL => $customerTransfer->getEmail(),
        ];
    }

    /**
     * @param string $paymentMethodName
     * @param string $paymentProviderName
     *
     * @return array
     */
    public function getPaymentRequestParams(
        string $paymentMethodName = self::REQUEST_PARAM_PAYMENT_METHOD_NAME_INVOICE,
        string $paymentProviderName = self::REQUEST_PARAM_PAYMENT_PROVIDER_NAME_DUMMY_PAYMENT
    ): array {
        return [
            [
                RestPaymentTransfer::PAYMENT_METHOD_NAME => $paymentMethodName,
                RestPaymentTransfer::PAYMENT_PROVIDER_NAME => $paymentProviderName,
            ],
        ];
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return array
     */
    public function getShipmentRequestParams(
        int $idShipmentMethod = self::REQUEST_PARAM_ID_SHIPMENT_METHOD_DEFAULT
    ): array {
        return [
            ShipmentMethodTransfer::ID_SHIPMENT_METHOD => $idShipmentMethod,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function authorizeCustomerToGlue(CustomerTransfer $customerTransfer): void
    {
        $oauthResponseTransfer = $this->haveAuthorizationToGlue($customerTransfer);
        $this->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());
    }

    /**
     * @param int $httpCode
     *
     * @return void
     */
    public function assertResponseHasCorrectInfrastructure(int $httpCode = HttpCode::CREATED): void
    {
        $this->seeResponseCodeIs($httpCode);
        $this->seeResponseIsJson();
        $this->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @return void
     */
    public function assertCheckoutResponseResourceHasCorrectData(): void
    {
        $idResource = $this->amSure('I\'m taking the the returned resource id')
            ->whenI()
            ->grabDataFromResponseByJsonPath('$.data.id');
        $this->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $this->amSure('I\'m taking the attributes from the returned resource')
            ->whenI()
            ->grabDataFromResponseByJsonPath('$.data.attributes');

        $this->assertNotEmpty(
            $attributes[RestCheckoutResponseTransfer::ORDER_REFERENCE],
            'The returned resource attributes order reference should not be empty'
        );
        $this->assertArrayHasKey(
            RestCheckoutResponseTransfer::IS_EXTERNAL_REDIRECT,
            $attributes,
            'The returned resource attributes should have an external redirect key'
        );
        $this->assertArrayHasKey(
            RestCheckoutResponseTransfer::REDIRECT_URL,
            $attributes,
            'The returned resource attributes should have a redirect URL key'
        );
    }

    /**
     * @return void
     */
    public function assertCheckoutDataResponseResourceHasCorrectData(): void
    {
        $idResource = $this->amSure('I\'m taking the the returned resource id')
            ->whenI()
            ->grabDataFromResponseByJsonPath('$.data.id');
        $this->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $this->amSure('I\'m taking the attributes from the returned resource')
            ->whenI()
            ->grabDataFromResponseByJsonPath('$.data.attributes');

        $this->assertEmpty(
            $attributes[RestCheckoutDataTransfer::ADDRESSES],
            'The returned resource attributes addresses should be an empty array'
        );
        $this->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS],
            'The returned resource attributes payment providers should not be an empty array'
        );
        $this->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS],
            'The returned resource attributes shipment methods should not be an empty array'
        );
    }

    /**
     * @return string
     */
    public function createGuestCustomerReference(): string
    {
        return uniqid('testReference', true);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function havePersistentQuoteWithItems(CustomerTransfer $customerTransfer, array $productConcreteTransfers): QuoteTransfer
    {
        return $this->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::TOTALS => (new TotalsBuilder())->build(),
            QuoteTransfer::ITEMS => $this->mapProductConcreteTransfersToQuoteTransferItems($productConcreteTransfers),
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
            QuoteTransfer::PRICE_MODE => PriceConfig::PRICE_MODE_GROSS,
            QuoteTransfer::BILLING_ADDRESS => (new AddressBuilder())->build(),
        ]);
    }

    /**
     * @param array $overrideCustomer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function haveEmptyPersistentQuote(array $overrideCustomer = []): QuoteTransfer
    {
        return $this->havePersistentQuote([
            QuoteTransfer::CUSTOMER => (new CustomerBuilder($overrideCustomer))->build(),
            QuoteTransfer::BILLING_ADDRESS => (new AddressBuilder())->build(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProductWithStock(): ProductConcreteTransfer
    {
        $productConcreteTransfer = $this->haveFullProduct();

        $this->haveProductInStockForStore($this->getStoreFacade()->getCurrentStore(), [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => 1,
        ]);

        $priceProductOverride = [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::PRICE_TYPE_NAME => 'DEFAULT',
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 7770,
                MoneyValueTransfer::GROSS_AMOUNT => 8880,
            ],
        ];
        $this->havePriceProduct($priceProductOverride);

        return $productConcreteTransfer;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomerTransfer(array $override = []): CustomerTransfer
    {
        return (new CustomerBuilder($override))->build();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveCustomerWithPersistentAddress(array $override = []): CustomerTransfer
    {
        $customerTransfer = $this->haveCustomer($override);

        return $this->haveAddressForCustomer($customerTransfer);
    }

    /***
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function haveAddressForCustomer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $addressTransfer = (new AddressBuilder([
            AddressTransfer::EMAIL => $customerTransfer->getEmail(),
            AddressTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            AddressTransfer::FIRST_NAME => $customerTransfer->getFirstName(),
            AddressTransfer::LAST_NAME => $customerTransfer->getLastName(),
        ]))->build();
        $customerFacade = $this->getCustomerFacade();
        $addressTransfer = $customerFacade->createAddress($addressTransfer);

        return $customerFacade->getCustomer($customerTransfer);
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
            $quoteTransferItems[] = (new ItemBuilder([
                ItemTransfer::SKU => $productConcreteTransfer->getSku(),
                ItemTransfer::GROUP_KEY => $productConcreteTransfer->getSku(),
                ItemTransfer::ABSTRACT_SKU => $productConcreteTransfer->getAbstractSku(),
                ItemTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                ItemTransfer::QUANTITY => 1,
            ]))->withShipment((new ShipmentBuilder())
                ->withMethod()
                ->withShippingAddress())
                ->build()
                ->modifiedToArray();
        }

        return $quoteTransferItems;
    }

    /**
     * @param string[] $includes
     *
     * @return string
     */
    protected function formatQueryInclude(array $includes = []): string
    {
        if (!$includes) {
            return '';
        }

        return sprintf('?%s=%s', RequestConstantsInterface::QUERY_INCLUDE, implode(',', $includes));
    }
}
