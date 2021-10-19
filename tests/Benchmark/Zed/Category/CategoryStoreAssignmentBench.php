<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Zed\Category;

use Benchmark\Zed\Category\PageObject\CategoryPage;
use Generated\Shared\Transfer\PhpBenchCsrfTokenConfigTransfer;
use Psr\Http\Message\ResponseInterface;
use SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface;
use SprykerSdk\Zed\Benchmark\Business\BenchmarkBusinessFactory;
use SprykerSdk\Zed\Benchmark\Business\Helper\Http\HttpHelperFactory;
use SprykerSdk\Zed\Benchmark\Business\Helper\Login\LoginHelperFactory;
use SprykerSdk\Zed\Benchmark\Business\Request\RequestBuilderFactory;

class CategoryStoreAssignmentBench
{
    /**
     * @var int
     */
    protected const CATEGORY_ID = 1;

    /**
     * @var string
     */
    protected const CATEGORY_KEY = 'demoshop';

    /**
     * @var string
     */
    protected const LOGIN_EMAIL = 'admin@spryker.com';

    /**
     * @var string
     */
    protected const LOGIN_PASSWORD = 'change123';

    /**
     * @var string
     */
    protected const CATEGORY_CSRF_FORM_ELEMENT_ID = 'category__token';

    /**
     * @var int
     */
    protected const ID_STORE_DE = 1;

    /**
     * @var int
     */
    protected const ID_STORE_AT = 2;

    /**
     * @var \SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface
     */
    protected $requestBuilder;

    /**
     * @var \SprykerSdk\Shared\Benchmark\Helper\Http\HttpHelperInterface
     */
    protected $httpHelper;

    /**
     * @var \GuzzleHttp\Cookie\CookieJarInterface
     */
    protected $cookieJar;

    /**
     * @var \SprykerSdk\Shared\Benchmark\Helper\CsrfToken\CsrfTokenHelperInterface
     */
    protected $csrfTokenHelper;

    /**
     * @return void
     */
    public function loginZedUser(): void
    {
        $this->requestBuilder = RequestBuilderFactory::createRequestBuilder();

        $loginHelper = LoginHelperFactory::createLoginHelper();
        $loginHelper->login(static::LOGIN_EMAIL, static::LOGIN_PASSWORD);

        $benchmarkBusinessFactory = new BenchmarkBusinessFactory();

        $this->cookieJar = $benchmarkBusinessFactory->getCookieJar();
        $this->csrfTokenHelper = $benchmarkBusinessFactory->createCsrfTokenHelper();
        $this->httpHelper = HttpHelperFactory::createHttpHelper();
    }

    /**
     * @BeforeMethods({"loginZedUser"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchRemoveTwoStoreAssignment(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_POST,
            sprintf(CategoryPage::CATEGORY_PAGE_EDIT_URL, static::CATEGORY_ID)
        );

        $options = [
            'cookies' => $this->cookieJar,
            'form_params' => $this->prepareRootRequestData([]),
        ];

        return $this->httpHelper->send($request, $options);
    }

    /**
     * @BeforeMethods({"loginZedUser"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchAddTwoStoreAssignment(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_POST,
            sprintf(CategoryPage::CATEGORY_PAGE_EDIT_URL, static::CATEGORY_ID)
        );

        $options = [
            'cookies' => $this->cookieJar,
            'form_params' => $this->prepareRootRequestData([static::ID_STORE_DE, static::ID_STORE_AT]),
        ];

        return $this->httpHelper->send($request, $options);
    }

    /**
     * @BeforeMethods({"loginZedUser"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchRemoveOneStoreAssignment(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_POST,
            sprintf(CategoryPage::CATEGORY_PAGE_EDIT_URL, static::CATEGORY_ID)
        );

        $options = [
            'cookies' => $this->cookieJar,
            'form_params' => $this->prepareRootRequestData([static::ID_STORE_DE]),
        ];

        return $this->httpHelper->send($request, $options);
    }

    /**
     * @BeforeMethods({"loginZedUser"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchAddOneStoreAssignment(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_POST,
            sprintf(CategoryPage::CATEGORY_PAGE_EDIT_URL, static::CATEGORY_ID)
        );

        $options = [
            'cookies' => $this->cookieJar,
            'form_params' => $this->prepareRootRequestData([static::ID_STORE_DE, static::ID_STORE_AT]),
        ];

        return $this->httpHelper->send($request, $options);
    }

    /**
     * @return \Generated\Shared\Transfer\PhpBenchCsrfTokenConfigTransfer
     */
    protected function createCsrfTokenConfigurationTransfer(): PhpBenchCsrfTokenConfigTransfer
    {
        return (new PhpBenchCsrfTokenConfigTransfer())
            ->setUrl(sprintf(CategoryPage::CATEGORY_PAGE_EDIT_URL, static::CATEGORY_ID))
            ->setElementId(static::CATEGORY_CSRF_FORM_ELEMENT_ID);
    }

    /**
     * @param array|null $stores
     *
     * @return array
     */
    protected function prepareRootRequestData(?array $stores = []): array
    {
        return [
            'category' => [
                'category_key' => static::CATEGORY_KEY,
                'is_active' => 1,
                'is_in_menu' => 1,
                'is_searchable' => 1,
                'fk_category_template' => 1,
                'store_relation' => [
                    'id_stores' => $stores,
                ],
                'is_root' => 1,
                '_token' => $this->csrfTokenHelper->getToken($this->createCsrfTokenConfigurationTransfer()),
                'localized_attributes' => [
                    [
                        'fk_locale' => 46,
                        'locale_name' => 'de_DE',
                        'name' => 'Kinderen 1-demoshop-1-DE',
                        'meta_title' => 'Kinderen 1-demoshop-1',
                        'meta_description' => 'Kinderen 1-demoshop-1',
                        'meta_keywords' => 'Kinderen 1-demoshop-1',
                    ],
                    [
                        'fk_locale' => 66,
                        'locale_name' => 'en_US',
                        'name' => 'Children 1-demoshop-1-EN',
                        'meta_title' => 'Children 1-demoshop-1',
                        'meta_description' => 'Children 1-demoshop-1',
                        'meta_keywords' => 'Children 1-demoshop-1',
                    ],
                ],
            ],
        ];
    }
}
