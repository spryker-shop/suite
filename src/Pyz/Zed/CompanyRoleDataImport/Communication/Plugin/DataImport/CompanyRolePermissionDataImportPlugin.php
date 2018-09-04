<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRolePermissionDataImportPlugin as SprykerCompanyRolePermissionDataImportPlugin;

/**
 * @method \Spryker\Zed\CompanyRoleDataImport\Business\CompanyRoleDataImportFacadeInterface getFacade()
 */
class CompanyRolePermissionDataImportPlugin extends SprykerCompanyRolePermissionDataImportPlugin
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        $dataImporterConfigurationTransfer = $this->setImporterFileName($dataImporterConfigurationTransfer);

        return parent::import($dataImporterConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function setImporterFileName(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterConfigurationTransfer
    {
        $dataImporterConfigurationTransfer = $dataImporterConfigurationTransfer ?? new DataImporterConfigurationTransfer();

        $dataImporterReaderConfigurationTransfer = $dataImporterConfigurationTransfer->getReaderConfiguration() ?? new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(APPLICATION_ROOT_DIR . '/data/import/company_role_permission.csv');

        return $dataImporterConfigurationTransfer;
    }
}
