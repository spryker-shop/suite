<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Form;

use Spryker\Zed\Form\FormDependencyProvider as SprykerFormDependencyProvider;
use Spryker\Zed\Gui\Communication\Plugin\Form\NoValidateTypeFormPlugin;

class FormDependencyProvider extends SprykerFormDependencyProvider
{
    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    protected function getFormPlugins(): array
    {
        return [
            new NoValidateTypeFormPlugin(),
        ];
    }
}
