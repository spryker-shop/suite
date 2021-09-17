<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\WebProfilerWidget;

use SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetConfig as SprykerWebProfilerWidgetConfig;
use Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;

class WebProfilerWidgetConfig extends SprykerWebProfilerWidgetConfig
{
    /**
     * @project Only needed in non-split projects.
     *
     * @return array<string>
     */
    public function getWebProfilerTemplatePaths(): array
    {
        if (!class_exists(WebDebugToolbarListener::class)) {
            return [];
        }

        return parent::getWebProfilerTemplatePaths();
    }
}
