<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductConfiguration\Plugin\ProductConfiguration;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;
use Spryker\Client\Session\SessionClient;

/**
 * @TODO this class would be fully reworked in 2nd phase. Will keep it for now as demo version.
 * @method \Pyz\Client\ProductConfiguration\ProductConfigurationFactory getFactory()
 */
class DemoProductConfiguratorRequestPlugin extends AbstractPlugin implements ProductConfiguratorRequestPluginInterface
{
    /**
     * @uses \Pyz\Yves\ProductConfiguratorGatewayPage\Plugin\Router\ProductConfiguratorGatewayPageRouteProviderPlugin::PRODUCT_CONFIGURATOR_PAGE_URL
     */
    protected const REDIRECT_PATH = '/dummy-configurator';
    /**
     * @uses \Pyz\Yves\ProductConfiguratorGatewayPage\Controller\ProductConfiguratorPageController::DUMMY_CONFIGURATOR_KEY
     */
    protected const DUMMY_CONFIGURATOR_KEY = 'dummy-configurator-key';
    protected const DUMMY_CONFIGURATOR_DATA_KEY = 'DUMMY_CONFIGURATOR_DATA_KEY';

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        $sessionClient = new SessionClient();
        $sessionClient->set(static::DUMMY_CONFIGURATOR_DATA_KEY, $productConfiguratorRequestTransfer);

        $redirectUrl = sprintf('%s%s', $this->getFactory()->getConfig()->getYvesBaseUrl(), static::REDIRECT_PATH);
        $redirectQueryPrams = http_build_query([static::DUMMY_CONFIGURATOR_KEY => static::DUMMY_CONFIGURATOR_DATA_KEY]);

        return (new ProductConfiguratorRedirectTransfer())
            ->setIsSuccessful(true)
            ->setConfiguratorRedirectUrl(sprintf('%s?%s', $redirectUrl, $redirectQueryPrams));
    }
}
