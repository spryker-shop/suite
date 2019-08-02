<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Form;

use Spryker\Yves\Form\FormDependencyProvider as SprykerFormDependencyProvider;
use Spryker\Yves\Form\Plugin\Form\CsrfFormPlugin;
use SprykerShop\Yves\WebProfilerWidget\Plugin\Form\WebProfilerFormPlugin;

class FormDependencyProvider extends SprykerFormDependencyProvider
{
    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    protected function getFormPlugins(): array
    {
        return [
            new CsrfFormPlugin(),
            new WebProfilerFormPlugin(),
        ];
    }
}
