<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CompanyPage;

use SprykerShop\Yves\CompanyPage\CompanyPageConfig as SprykerShopCompanyPageConfig;

class CompanyPageConfig extends SprykerShopCompanyPageConfig
{
    protected const ZIP_CODE_CONSTRAINT_PATTERN = '/^\d+$/';

    /**
     * @return string
     */
    public function getZipCodeConstraintPattern(): string
    {
        return static::ZIP_CODE_CONSTRAINT_PATTERN;
    }
}
