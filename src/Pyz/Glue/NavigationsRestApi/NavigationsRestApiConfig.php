<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\NavigationsRestApi;

use Spryker\Glue\NavigationsRestApi\NavigationsRestApiConfig as SprykerNavigationsRestApiConfigi;

class NavigationsRestApiConfig extends SprykerNavigationsRestApiConfigi
{
    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getNavigationTypeToUrlResourceIdFieldMapping(): array
    {
        return [
            'category' => 'fkResourceCategorynode',
            'cms_page' => 'fkResourcePage',
        ];
    }
}
