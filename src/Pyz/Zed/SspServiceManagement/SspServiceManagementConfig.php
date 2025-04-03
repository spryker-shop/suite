<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\SspServiceManagement;

use Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer;
use SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig as SprykerSspServiceManagementConfig;

class SspServiceManagementConfig extends SprykerSspServiceManagementConfig
{
    /**
     * @return string
     */
    public function getDefaultMerchantReference(): string
    {
        return 'MER000001';
    }

    /**
     * Specification:
     * - Import configuration for product shipment type.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductShipmentTypeDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        $dataImporterDataSourceConfigurationTransfer = parent::getProductShipmentTypeDataImporterConfiguration();

        return $dataImporterDataSourceConfigurationTransfer->setModuleName('ssp-service-management');
    }

    /**
     * Specification:
     * - Import configuration for product abstract type.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductAbstractTypeDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        $dataImporterDataSourceConfigurationTransfer = parent::getProductAbstractTypeDataImporterConfiguration();

        return $dataImporterDataSourceConfigurationTransfer->setModuleName('ssp-service-management');
    }

    /**
     * Specification:
     * - Import configuration for product abstract to product abstract type relation.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductAbstractToProductAbstractTypeDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        $dataImporterDataSourceConfigurationTransfer = parent::getProductAbstractToProductAbstractTypeDataImporterConfiguration();

        return $dataImporterDataSourceConfigurationTransfer->setModuleName('ssp-service-management');
    }
}
