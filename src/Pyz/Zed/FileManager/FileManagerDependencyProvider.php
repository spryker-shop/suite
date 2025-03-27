<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\FileManager;

use Spryker\Zed\FileManager\FileManagerDependencyProvider as SprykerFileManagerDependencyProvider;
use SprykerFeature\Zed\SspAssetManagement\Communication\Plugin\FileManager\SspAssetManagementFilePreDeletePlugin;
use SprykerFeature\Zed\SspFileManagement\Communication\Plugin\FileManager\FileAttachmentFilePreDeletePlugin;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\FileManager\SspInquiryManagementFilePreDeletePlugin;

class FileManagerDependencyProvider extends SprykerFileManagerDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\FileManagerExtension\Dependency\Plugin\FilePreDeletePluginInterface>
     */
    protected function getFilePreDeletePlugins(): array
    {
        return [
            new FileAttachmentFilePreDeletePlugin(),
            new SspInquiryManagementFilePreDeletePlugin(),
            new SspAssetManagementFilePreDeletePlugin(),
        ];
    }
}
