<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\MerchantFile;

use Spryker\Shared\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig;
use Spryker\Zed\MerchantFile\MerchantFileConfig as SprykerMerchantFileConfig;

class MerchantFileConfig extends SprykerMerchantFileConfig
{
    /**
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getFileTypeToContentTypeMapping(): array
    {
        return [
            FileImportMerchantPortalGuiConfig::FILE_TYPE_DATA_IMPORT => [
                'text/csv',
                'application/csv',
                'text/plain',
            ],
        ];
    }
}
