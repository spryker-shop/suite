<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\ResourceSharePage;

use SprykerShop\Yves\PersistentCartSharePage\Plugin\CartPreviewRouterStrategyPlugin;
use SprykerShop\Yves\ResourceSharePage\ResourceSharePageDependencyProvider as SprykerResourceSharePageDependencyProvider;

class ResourceSharePageDependencyProvider extends SprykerResourceSharePageDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[]
     */
    protected function getResourceShareRouterStrategyPlugins(): array
    {
        return [
            new CartPreviewRouterStrategyPlugin(),
        ];
    }
}
