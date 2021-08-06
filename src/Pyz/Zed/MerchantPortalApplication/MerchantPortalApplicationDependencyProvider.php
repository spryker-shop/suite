<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantPortalApplication;

use Spryker\Zed\AclEntity\Communication\Plugin\Application\AclEntityApplicationPlugin;
use Spryker\Zed\ErrorHandler\Communication\Plugin\Application\ErrorHandlerApplicationPlugin;
use Spryker\Zed\EventDispatcher\Communication\Plugin\Application\EventDispatcherApplicationPlugin;
use Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin;
use Spryker\Zed\GuiTable\Communication\Plugin\Application\GuiTableApplicationPlugin;
use Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin;
use Spryker\Zed\Locale\Communication\Plugin\Application\LocaleApplicationPlugin;
use Spryker\Zed\MerchantPortalApplication\MerchantPortalApplicationDependencyProvider as SprykerMerchantPortalApplicationDependencyProvider;
use Spryker\Zed\Messenger\Communication\Plugin\Application\MessengerApplicationPlugin;
use Spryker\Zed\Propel\Communication\Plugin\Application\PropelApplicationPlugin;
use Spryker\Zed\Router\Communication\Plugin\Application\MerchantPortalRouterApplicationPlugin;
use Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin;
use Spryker\Zed\Session\Communication\Plugin\Application\SessionApplicationPlugin;
use Spryker\Zed\Translator\Communication\Plugin\Application\TranslatorApplicationPlugin;
use Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin;
use Spryker\Zed\Validator\Communication\Plugin\Application\ValidatorApplicationPlugin;
use Spryker\Zed\ZedUi\Communication\Plugin\Application\ZedUiApplicationPlugin;

class MerchantPortalApplicationDependencyProvider extends SprykerMerchantPortalApplicationDependencyProvider
{
    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getMerchantPortalApplicationPlugins(): array
    {
        return [
            new SessionApplicationPlugin(),
            new TwigApplicationPlugin(),
            new EventDispatcherApplicationPlugin(),
            new LocaleApplicationPlugin(),
            new TranslatorApplicationPlugin(),
            new MessengerApplicationPlugin(),
            new PropelApplicationPlugin(),
            new MerchantPortalRouterApplicationPlugin(),
            new HttpApplicationPlugin(),
            new ErrorHandlerApplicationPlugin(),
            new FormApplicationPlugin(),
            new ValidatorApplicationPlugin(),
            new GuiTableApplicationPlugin(),
            new SecurityApplicationPlugin(),
            new ZedUiApplicationPlugin(),
            new AclEntityApplicationPlugin(),
        ];
    }
}
