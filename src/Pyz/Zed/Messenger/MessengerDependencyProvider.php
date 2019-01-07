<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Messenger;

use Spryker\Zed\Glossary\Communication\Plugin\TranslationPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Messenger\MessengerDependencyProvider as SprykerMessengerDependencyProvider;
use Spryker\Zed\Translator\Communication\Plugin\TranslationPlugin as FallbackTranslationPlugin;

class MessengerDependencyProvider extends SprykerMessengerDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslationPlugin(Container $container)
    {
        $container[static::PLUGIN_TRANSLATION] = function (Container $container) {
            return new TranslationPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFallbackTranslationPlugin(Container $container)
    {
        $container[static::PLUGIN_FALLBACK_TRANSLATION] = function (Container $container) {
            return new FallbackTranslationPlugin();
        };

        return $container;
    }
}
