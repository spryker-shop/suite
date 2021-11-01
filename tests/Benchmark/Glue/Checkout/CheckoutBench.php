<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Glue\Checkout;

use Benchmark\Glue\Cart\PageObject\CartPage;
use Benchmark\Glue\Checkout\PageObject\CheckoutPage;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\RestPaymentBuilder;
use Psr\Http\Message\ResponseInterface;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use SprykerSdk\Glue\Benchmark\Helper\Http\HttpHelperFactory;
use SprykerSdk\Glue\Benchmark\Request\RequestBuilderFactory;
use SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface;

class CheckoutBench
{
    /**
     * @var string
     */
    protected const PRODUCT_SKU = '035_17360369';

    /**
     * @var string
     */
    protected $idCart;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var array
     */
    protected $requestData = [];

    /**
     * @var \SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface
     */
    protected $requestBuilder;

    /**
     * @var \SprykerSdk\Shared\Benchmark\Helper\Http\HttpHelperInterface
     */
    protected $httpHelper;

    /**
     * @return void
     */
    public function prepareRequestHelpers(): void
    {
        $this->requestBuilder = RequestBuilderFactory::createRequestBuilder();
        $this->httpHelper = HttpHelperFactory::createHttpHelper();
    }

    /**
     * @return void
     */
    public function prepareCartId(): void
    {
        $this->customerId = uniqid('', true);
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_POST,
            CartPage::ENDPOINT_GUEST_CART,
            [
                'X-Anonymous-Customer-Unique-Id' => $this->customerId,
            ],
            [
                'data' => [
                    'type' => 'guest-cart-items',
                    'attributes' => [
                        'sku' => static::PRODUCT_SKU,
                        'quantity' => 1,
                    ],
                ],
            ],
        );

        $response = $this->httpHelper->send($request, [], 201);

        $utilEncodingService = new UtilEncodingService();
        $data = $utilEncodingService->decodeJson($response->getBody()->getContents(), true);

        $this->idCart = $data['data']['id'];
    }

    /**
     * @return void
     */
    public function prepareCheckoutRequestData(): void
    {
        $addressData = (new AddressBuilder())->build()->toArray(true, true);
        $customerData = (new CustomerBuilder())->build()->toArray(true, true);
        $addressData = array_merge($addressData, $customerData);

        $this->requestData = [
            'data' => [
                'type' => 'checkout-data',
                'attributes' => [
                    'customer' => $customerData,
                    'idCart' => $this->idCart,
                    'billingAddress' => $addressData,
                    'shippingAddress' => $addressData,
                    'payments' => [
                        (new RestPaymentBuilder())->build()->toArray(true, true),
                    ],
                    'shipment' => [
                        'idShipmentMethod' => 1,
                    ],
                ],
            ],
        ];
    }

    /**
     * @BeforeMethods({"prepareRequestHelpers", "prepareCartId", "prepareCheckoutRequestData"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchCheckout(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_POST,
            CheckoutPage::ENDPOINT_CHECKOUT,
            [
                'X-Anonymous-Customer-Unique-Id' => $this->customerId,
            ],
            $this->requestData,
        );

        return $this->httpHelper->send($request);
    }
}
