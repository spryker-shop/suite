<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Zed\HomePage;

use Benchmark\Zed\HomePage\PageObject\HomePage;
use Psr\Http\Message\ResponseInterface;
use SprykerSdk\Zed\Benchmark\Business\Helper\Http\HttpHelperFactory;
use SprykerSdk\Zed\Benchmark\Business\Helper\Login\LoginHelperFactory;
use SprykerSdk\Zed\Benchmark\Business\Request\RequestBuilder;
use SprykerSdk\Zed\Benchmark\Business\Request\RequestBuilderFactory;

class HomeZedPageBench
{
    /**
     * @var string
     */
    protected const LOGIN_EMAIL = 'admin@spryker.com';
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
    public function beforeZedHomePageOpens(): void
    {
        $this->requestBuilder = RequestBuilderFactory::createRequestBuilder();

        $loginHelper = LoginHelperFactory::createLoginHelper();
        $this->loginHeader = $loginHelper->login(static::LOGIN_EMAIL, static::LOGIN_PASSWORD);

        $this->httpHelper = HttpHelperFactory::createHttpHelper();
    }

    /**
     * @BeforeMethods({"beforeZedHomePageOpens"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchZedHomePageOpens(): ResponseInterface
    {
        $headers[$this->loginHeader->getName()] = $this->loginHeader->getValue();
        $request = $this->requestBuilder->buildRequest(
            RequestBuilder::METHOD_GET,
            HomePage::HOME_PAGE_URL,
            $headers
        );

        return $this->httpHelper->send($request);
    }
}
