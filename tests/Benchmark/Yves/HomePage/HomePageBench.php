<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Yves\HomePage;

use Benchmark\Yves\HomePage\PageObject\HomePage;
use Psr\Http\Message\ResponseInterface;
use SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface;
use SprykerSdk\Yves\Benchmark\Helper\Http\HttpHelperFactory;
use SprykerSdk\Yves\Benchmark\Helper\Login\LoginHelperFactory;
use SprykerSdk\Yves\Benchmark\Request\RequestBuilderFactory;

class HomePageBench
{
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
     * @var \SprykerSdk\Shared\Benchmark\Helper\Http\HttpHelperInterface
     */
    protected $httpHelper;

    /**
     * @var \Generated\Shared\Transfer\LoginHeaderTransfer
     */
    protected $loginHeader;

    /**
     * @return void
     */
    public function prepareRequestHelpers(): void
    {
        $this->requestBuilder = RequestBuilderFactory::createRequestBuilder();
        $this->httpHelper = HttpHelperFactory::createHttpHelper();
    }

    /**
     * @BeforeMethods({"prepareRequestHelpers"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchHomePageOpensForNotLoggedCustomer(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(RequestBuilderInterface::METHOD_GET, HomePage::HOME_PAGE_URL);

        return $this->httpHelper->send($request);
    }

    /**
     * @return void
     */
    public function beforeHomePageOpensForLoggedCustomer(): void
    {
        $loginHelper = LoginHelperFactory::createLoginHelper();

        $this->loginHeader = $loginHelper->login(static::LOGIN_EMAIL, static::LOGIN_PASSWORD);
    }

    /**
     * @BeforeMethods({"prepareRequestHelpers", "beforeHomePageOpensForLoggedCustomer"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchHomePageOpensForLoggedCustomer(): ResponseInterface
    {
        $headers = [
            $this->loginHeader->getName() => $this->loginHeader->getValue(),
        ];

        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_GET,
            HomePage::HOME_PAGE_URL,
            $headers,
        );

        return $this->httpHelper->send($request);
    }
}
