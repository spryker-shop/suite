<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\ExampleProductSalePage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ProductLabelStorage\ProductLabelStorageClientInterface;
use Spryker\Client\Search\SearchClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\Store\StoreClientInterface;

class ExampleProductSalePageFactory extends AbstractFactory
{
    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getSaleSearchQueryPlugin(array $requestParameters = []): QueryInterface
    {
        $saleQueryPlugin = $this->getProvidedDependency(ExampleProductSalePageDependencyProvider::SALE_SEARCH_QUERY_PLUGIN);

        return $this->getSearchClient()->expandQuery(
            $saleQueryPlugin,
            $this->getSaleSearchQueryExpanderPlugins(),
            $requestParameters,
        );
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\ProductLabelStorageClientInterface
     */
    public function getProductLabelStorageClient(): ProductLabelStorageClientInterface
    {
        return $this->getProvidedDependency(ExampleProductSalePageDependencyProvider::CLIENT_PRODUCT_LABEL_STORAGE);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(ExampleProductSalePageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\Locale\LocaleClient
     */
    public function getLocaleClient(): LocaleClientInterface
    {
        return $this->getProvidedDependency(ExampleProductSalePageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Pyz\Client\ExampleProductSalePage\ExampleProductSalePageConfig
     */
    public function getConfig(): ExampleProductSalePageConfig
    {
        /** @var \Pyz\Client\ExampleProductSalePage\ExampleProductSalePageConfig $config */
        $config = parent::getConfig();

        return $config;
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient(): SearchClientInterface
    {
        return $this->getProvidedDependency(ExampleProductSalePageDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function getSaleSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ExampleProductSalePageDependencyProvider::SALE_SEARCH_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getSaleSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(ExampleProductSalePageDependencyProvider::SALE_SEARCH_RESULT_FORMATTER_PLUGINS);
    }
}
