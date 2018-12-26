<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueApplication;

use Spryker\Glue\GlueApplication\GlueApplicationConfig as SprykerGlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;

class GlueApplicationConfig extends SprykerGlueApplicationConfig
{
    /**
     * @return array
     */
    public function getCorsAllowedHeaders(): array
    {
        return array_merge(
            parent::getCorsAllowedHeaders(),
            [RequestConstantsInterface::HEADER_X_ANONYMOUS_CUSTOMER_UNIQUE_ID]
        );
    }
}
