<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Yves\Cart;

use Benchmark\Yves\Cart\PageObject\CartPage;
use Psr\Http\Message\ResponseInterface;
use SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface;
use SprykerSdk\Yves\Benchmark\Helper\Http\HttpHelperFactory;
use SprykerSdk\Yves\Benchmark\Helper\Login\LoginHelperFactory;
use SprykerSdk\Yves\Benchmark\Request\RequestBuilderFactory;

class AddToCartBench
{
    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_SKU = '066_23294028';

    /**
     * @var string
     */
    protected const LOGIN_EMAIL = 'spencor.hopkin@spryker.com';

    /**
     * @var string
     */
    protected const LOGIN_PASSWORD = 'change123';

    /**
     * @var \SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface
     */
    protected $requestBuilder;

    /**
     * @var \Generated\Shared\Transfer\LoginHeaderTransfer
     */
    protected $loginHeader;

    /**
     * @var \SprykerSdk\Shared\Benchmark\Helper\Http\HttpHelperInterface
     */
    protected $httpHelper;

    /**
     * @return void
     */
    public function beforeAddingOneItemToCart(): void
    {
        $this->requestBuilder = RequestBuilderFactory::createRequestBuilder();
        $this->httpHelper = HttpHelperFactory::createHttpHelper();
        $loginHelper = LoginHelperFactory::createLoginHelper();

        $this->loginHeader = $loginHelper->login(static::LOGIN_EMAIL, static::LOGIN_PASSWORD);
    }

    /**
     * @BeforeMethods({"beforeAddingOneItemToCart"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchAddingOneItemToCart(): ResponseInterface
    {
        $headers = [
            $this->loginHeader->getName() => $this->loginHeader->getValue(),
        ];

        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_POST,
            sprintf(CartPage::ADD_TO_CART_URL, static::PRODUCT_CONCRETE_SKU),
            $headers,
        );

        return $this->httpHelper->send($request);
    }
}
