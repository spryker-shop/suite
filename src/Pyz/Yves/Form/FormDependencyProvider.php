<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\Form;

use Spryker\Yves\Form\FormDependencyProvider as SprykerFormDependencyProvider;
use Spryker\Yves\Form\Plugin\Form\CsrfFormPlugin;
use Spryker\Yves\Http\Plugin\Form\HttpFoundationTypeExtensionFormPlugin;
use Spryker\Yves\MultiFactorAuth\Plugin\Form\MultiFactorAuthExtensionFormPlugin;
use Spryker\Yves\Validator\Plugin\Form\ValidatorExtensionFormPlugin;
use SprykerShop\Yves\ShopUi\Plugin\Form\SanitizeXssTypeExtensionFormPlugin;
use SprykerShop\Yves\WebProfilerWidget\Plugin\Form\WebProfilerFormPlugin;

class FormDependencyProvider extends SprykerFormDependencyProvider
{
    /**
     * @return array<\Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface>
     */
    protected function getFormPlugins(): array
    {
        $plugins = [
            new ValidatorExtensionFormPlugin(),
            new HttpFoundationTypeExtensionFormPlugin(),
            new CsrfFormPlugin(),
            new SanitizeXssTypeExtensionFormPlugin(),
            new MultiFactorAuthExtensionFormPlugin(),
        ];

        if (class_exists(WebProfilerFormPlugin::class)) {
            $plugins[] = new WebProfilerFormPlugin();
        }

        return $plugins;
    }
}
