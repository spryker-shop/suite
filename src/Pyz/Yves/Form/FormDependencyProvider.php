<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Form;

use Spryker\Shared\Form\Plugin\Form\DoubleSubmitProtectionFormPlugin;
use Spryker\Shared\Security\Plugin\Form\CsrfFormPlugin;
use Spryker\Shared\WebProfiler\Plugin\Form\WebProfilerFormPlugin;
use Spryker\Yves\Form\FormDependencyProvider as SprykerFormDependencyProvider;

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
            new WebProfilerFormPlugin(),
        ]);
    }
}
