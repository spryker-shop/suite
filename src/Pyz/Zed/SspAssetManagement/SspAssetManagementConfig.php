<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\SspAssetManagement;

use SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig as SprykerFeatureSspAssetManagementConfig;

class SspAssetManagementConfig extends SprykerFeatureSspAssetManagementConfig
{
    /**
     * @return string|null
     */
    public function getStorageName(): ?string
    {
        return 'ssp-asset-image';
    }
}
