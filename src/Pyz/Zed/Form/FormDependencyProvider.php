<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Form;

use Spryker\Shared\Form\Plugin\Form\DoubleSubmitProtectionFormPlugin;
use Spryker\Shared\Security\Plugin\Form\CsrfFormPlugin;
use Spryker\Shared\WebProfiler\Plugin\Form\WebProfilerFormPlugin;
use Spryker\Zed\Form\FormDependencyProvider as SprykerFormDependencyProvider;
use Spryker\Zed\Gui\Communication\Plugin\Form\NoValidateTypeFormPlugin;

class FormDependencyProvider extends SprykerFormDependencyProvider
{
    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    protected function getFormPlugins(): array
    {
        return array_merge(parent::getFormPlugins(), [
            new DoubleSubmitProtectionFormPlugin(),
            new CsrfFormPlugin(),
            new NoValidateTypeFormPlugin(),
            new WebProfilerFormPlugin(),
        ]);
    }
}
