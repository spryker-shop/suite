<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyRoleDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\CompanyRoleDataImport\CompanyRoleDataImportConfig as SprykerCompanyRoleDataImportConfig;

class CompanyRoleDataImportConfig extends SprykerCompanyRoleDataImportConfig
{
    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCompanyRolePermissionDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            implode(DIRECTORY_SEPARATOR, [$this->getProjectDataImportDirectory(), 'company_role_permission.csv']),
            static::IMPORT_TYPE_COMPANY_ROLE_PERMISSION
        );
    }

    /**
     * @return string
     */
    protected function getProjectDataImportDirectory(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
                $this->getProjectDirectory(),
                'data',
                'import',
            ]) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function getProjectDirectory(): string
    {
        return realpath(APPLICATION_ROOT_DIR) . DIRECTORY_SEPARATOR;
    }
}
