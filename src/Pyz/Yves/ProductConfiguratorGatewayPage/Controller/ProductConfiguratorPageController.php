<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductConfiguratorGatewayPage\Controller;

use Spryker\Client\Session\SessionClient;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory getFactory()
 */
class ProductConfiguratorPageController extends AbstractController
{
    //@TODO this file will be removed in 2nd phase of configurable product. Will keep it for now as demo version

    /**
     * @uses \Pyz\Client\ProductConfiguration\Plugin\ProductConfiguration\DemoProductConfiguratorRequestPlugin::DUMMY_CONFIGURATOR_DATA_KEY
     */
    protected const DUMMY_CONFIGURATOR_KEY = 'dummy-configurator-key';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $sessionClient = new SessionClient();
        /** @var \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer */
        $productConfiguratorRequestTransfer = $sessionClient->get($request->get(static::DUMMY_CONFIGURATOR_KEY));

        return $this->view(
            [
                'productConfiguratorRequestDataTransfer'
                    => $productConfiguratorRequestTransfer->getProductConfiguratorRequestData(),
            ],
            [],
            '@ProductConfiguratorGatewayPage/views/product-configurator-page/product-configurator-page.twig'
        );
    }
}
