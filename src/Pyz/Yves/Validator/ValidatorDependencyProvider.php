<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Validator;

use Spryker\Shared\Translator\Plugin\Validator\TranslatorValidatorPlugin;
use Spryker\Yves\Validator\ValidatorDependencyProvider as SprykerValidatorDependencyProvider;

class ValidatorDependencyProvider extends SprykerValidatorDependencyProvider
{
    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface[]
     */
    protected function getValidatorPlugins(): array
    {
        return array_merge(parent::getValidatorPlugins(), [
            new TranslatorValidatorPlugin(),
        ]);
    }
}
