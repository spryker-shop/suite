<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueApplication;

use Spryker\Glue\GlueApplication\GlueApplicationConfig as SprykerGlueApplicationConfig;

class GlueApplicationConfig extends SprykerGlueApplicationConfig
{
    /**
     * @return bool
     */
    public function getIsEagerRelationshipsLoadingEnabled(): bool
    {
        return false;
    }
}
