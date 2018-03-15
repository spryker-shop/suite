<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ShopApplication;

use Pyz\Yves\ExampleProductSalePage\Plugin\Provider\ExampleProductSaleControllerProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\RememberMeServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Spryker\Service\UtilDateTime\ServiceProvider\DateTimeFormatterServiceProvider;
use Spryker\Shared\Application\ServiceProvider\FormFactoryServiceProvider;
use Spryker\Shared\Application\ServiceProvider\HeadersSecurityServiceProvider;
use Spryker\Shared\Application\ServiceProvider\RoutingServiceProvider;
use Spryker\Shared\Application\ServiceProvider\UrlGeneratorServiceProvider;
use Spryker\Yves\Application\ApplicationConfig;
use Spryker\Yves\Application\Plugin\Provider\CookieServiceProvider;
use Spryker\Yves\Application\Plugin\Provider\YvesHstsServiceProvider;
use Spryker\Yves\Application\Plugin\ServiceProvider\KernelLogServiceProvider;
use Spryker\Yves\Application\Plugin\ServiceProvider\SslServiceProvider;
use Spryker\Yves\CmsContentWidget\Plugin\CmsContentWidgetServiceProvider;
use Spryker\Yves\Kernel\Application;
use Spryker\Yves\Messenger\Plugin\Provider\FlashMessengerServiceProvider;
use Spryker\Yves\NewRelic\Plugin\ServiceProvider\NewRelicRequestTransactionServiceProvider;
use Spryker\Yves\Session\Plugin\ServiceProvider\SessionServiceProvider as SprykerSessionServiceProvider;
use Spryker\Yves\Storage\Plugin\Provider\StorageCacheServiceProvider;
use Spryker\Yves\Twig\Plugin\ServiceProvider\TwigServiceProvider as SprykerTwigServiceProvider;
use Spryker\Yves\ZedRequest\Plugin\ServiceProvider\ZedRequestHeaderServiceProvider;
use Spryker\Yves\ZedRequest\Plugin\ServiceProvider\ZedRequestLogServiceProvider;
use SprykerShop\Yves\CalculationPage\Plugin\Provider\CalculationPageControllerProvider;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartServiceProvider;
use SprykerShop\Yves\CatalogPage\Plugin\Provider\CatalogPageControllerProvider;
use SprykerShop\Yves\CatalogPage\Plugin\Provider\CatalogPageTwigServiceProvider;
use SprykerShop\Yves\CategoryWidget\Plugin\Provider\CategoryServiceProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\CmsBlockWidget\Plugin\Provider\CmsBlockTwigFunctionServiceProvider;
use SprykerShop\Yves\CmsPage\Plugin\Provider\CmsTwigFunctionServiceProvider;
use SprykerShop\Yves\CmsPage\Plugin\Provider\PreviewControllerProvider;
use SprykerShop\Yves\CurrencyWidget\Plugin\Provider\CurrencyWidgetControllerProvider;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerSecurityServiceProvider;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerTwigFunctionServiceProvider;
use SprykerShop\Yves\DiscountWidget\Plugin\Provider\DiscountWidgetControllerProvider;
use SprykerShop\Yves\ErrorPage\Plugin\Provider\ErrorPageControllerProvider;
use SprykerShop\Yves\ErrorPage\Plugin\Provider\ErrorPageServiceProvider;
use SprykerShop\Yves\HeartbeatPage\Plugin\Provider\HeartbeatPageControllerProvider;
use SprykerShop\Yves\HomePage\Plugin\Provider\HomePageControllerProvider;
use SprykerShop\Yves\MoneyWidget\Plugin\ServiceProvider\TwigMoneyServiceProvider;
use SprykerShop\Yves\NewsletterPage\Plugin\Provider\NewsletterPageControllerProvider;
use SprykerShop\Yves\NewsletterWidget\Plugin\Provider\NewsletterWidgetControllerProvider;
use SprykerShop\Yves\PriceWidget\Plugin\Provider\PriceControllerProvider;
use SprykerShop\Yves\ProductNewPage\Plugin\Provider\ProductNewPageControllerProvider;
use SprykerShop\Yves\ProductReviewWidget\Plugin\Provider\ProductReviewControllerProvider;
use SprykerShop\Yves\ProductSetListPage\Plugin\Provider\ProductSetListPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AutoloaderCacheServiceProvider;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\ShopApplicationServiceProvider;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\ShopTwigServiceProvider;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\WidgetServiceProvider;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\YvesExceptionServiceProvider;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\YvesSecurityServiceProvider;
use SprykerShop\Yves\ShopRouter\Plugin\Router\SilexRouter;
use SprykerShop\Yves\ShopRouter\Plugin\Router\StorageRouter;
use SprykerShop\Yves\ShopTranslator\Plugin\Provider\TranslationServiceProvider;
use SprykerShop\Yves\ShopUi\Plugin\Provider\ShopUiTwigServiceProvider;
use SprykerShop\Yves\WebProfilerWidget\Plugin\ServiceProvider\WebProfilerWidgetServiceProvider;
use SprykerShop\Yves\WishlistPage\Plugin\Provider\WishlistPageControllerProvider;

class YvesBootstrap
{
    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @var \Spryker\Yves\Application\ApplicationConfig
     */
    protected $config;

    public function __construct()
    {
        $this->application = new Application();
        $this->config = new ApplicationConfig();
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function boot()
    {
        $this->registerServiceProviders();
        $this->registerRouters();
        $this->registerControllerProviders();

        return $this->application;
    }

    /**
     * @return void
     */
    protected function registerServiceProviders()
    {
        $this->application->register(new SslServiceProvider());
        $this->application->register(new StorageCacheServiceProvider());
        $this->application->register(new KernelLogServiceProvider());
        $this->application->register(new ZedRequestHeaderServiceProvider());
        $this->application->register(new ZedRequestLogServiceProvider());

        $this->application->register(new ShopTwigServiceProvider());
        $this->application->register(new SprykerTwigServiceProvider());
        $this->application->register(new WidgetServiceProvider());
        $this->application->register(new ShopApplicationServiceProvider());
        $this->application->register(new DateTimeFormatterServiceProvider());
        $this->application->register(new SessionServiceProvider());
        $this->application->register(new SprykerSessionServiceProvider());
        $this->application->register(new SecurityServiceProvider());
        $this->application->register(new CustomerSecurityServiceProvider());
        $this->application->register(new CustomerTwigFunctionServiceProvider());
        $this->application->register(new YvesSecurityServiceProvider());
        $this->application->register(new YvesExceptionServiceProvider());
        $this->application->register(new ErrorPageServiceProvider());
        $this->application->register(new NewRelicRequestTransactionServiceProvider());
        $this->application->register(new CookieServiceProvider());
        $this->application->register(new UrlGeneratorServiceProvider());
        $this->application->register(new ServiceControllerServiceProvider());
        $this->application->register(new RememberMeServiceProvider());
        $this->application->register(new RoutingServiceProvider());
        $this->application->register(new TranslationServiceProvider());
        $this->application->register(new ValidatorServiceProvider());
        $this->application->register(new FormServiceProvider());
        $this->application->register(new HttpFragmentServiceProvider());
        $this->application->register(new CategoryServiceProvider());
        $this->application->register(new FlashMessengerServiceProvider());
        $this->application->register(new HeadersSecurityServiceProvider());
        $this->application->register(new WebProfilerWidgetServiceProvider());
        $this->application->register(new AutoloaderCacheServiceProvider());
        $this->application->register(new YvesHstsServiceProvider());
        $this->application->register(new CartServiceProvider());
        $this->application->register(new FormFactoryServiceProvider());
        $this->application->register(new TwigMoneyServiceProvider());
        $this->application->register(new CmsContentWidgetServiceProvider());
        $this->application->register(new CmsTwigFunctionServiceProvider());
        $this->application->register(new CmsBlockTwigFunctionServiceProvider());
        $this->application->register(new CatalogPageTwigServiceProvider());
        $this->application->register(new ShopUiTwigServiceProvider());
    }

    /**
     * @return void
     */
    protected function registerRouters()
    {
        $this->application->addRouter((new StorageRouter())->setSsl(false));
        $this->application->addRouter(new SilexRouter());
    }

    /**
     * @return void
     */
    protected function registerControllerProviders()
    {
        $isSsl = $this->config->isSslEnabled();

        $controllerProviders = $this->getControllerProviderStack($isSsl);

        foreach ($controllerProviders as $controllerProvider) {
            $this->application->mount($controllerProvider->getUrlPrefix(), $controllerProvider);
        }
    }

    /**
     * @param bool|null $isSsl
     *
     * @return \SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider[]
     */
    protected function getControllerProviderStack($isSsl)
    {
        return [
            new ErrorPageControllerProvider($isSsl),
            new HomePageControllerProvider($isSsl),
            new CheckoutPageControllerProvider($isSsl),
            new CustomerPageControllerProvider($isSsl),
            new NewsletterPageControllerProvider($isSsl),
            new CartControllerProvider($isSsl),
            new WishlistPageControllerProvider($isSsl),
            new HeartbeatPageControllerProvider($isSsl),
            new NewsletterWidgetControllerProvider($isSsl),
            new CatalogPageControllerProvider($isSsl),
            new CalculationPageControllerProvider($isSsl),
            new ProductSetListPageControllerProvider($isSsl),
            new ExampleProductSaleControllerProvider($isSsl),
            new PreviewControllerProvider($isSsl),
            new CurrencyWidgetControllerProvider($isSsl),
            new ProductNewPageControllerProvider($isSsl),
            new ProductReviewControllerProvider($isSsl),
            new DiscountWidgetControllerProvider($isSsl),
            new PriceControllerProvider($isSsl),
        ];
    }
}
