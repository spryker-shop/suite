<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Messenger;

use Spryker\Zed\Glossary\Communication\Plugin\TranslationPlugin as GlossaryTranslationPlugin;
use Spryker\Zed\Messenger\MessengerDependencyProvider as SprykerMessengerDependencyProvider;
use Spryker\Zed\Translator\Communication\Plugin\TranslationPlugin;

class MessengerDependencyProvider extends SprykerMessengerDependencyProvider
{
    /**
     * For correct work of ZED translations TranslationPlugin must be added to the end of the array.
     *
     * @return \Spryker\Zed\MessengerExtension\Dependency\Plugin\TranslationPluginInterface[]
     */
    protected function getTranslationPlugins(): array
    {
        return [
            new GlossaryTranslationPlugin(),
            new TranslationPlugin(),
        ];
    }
}
