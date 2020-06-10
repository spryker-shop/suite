<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CmsPageSearch;

use DateTime;
use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\CmsPageSearch\CmsPageSearchConfig as SprykerCmsPageSearchConfig;

class CmsPageSearchConfig extends SprykerCmsPageSearchConfig
{
    protected const KEY_UUID = 'uuid';
    protected const KEY_VALID_TO = 'valid_to';
    protected const KEY_IS_SEARCHABLE = 'is_searchable';
    protected const KEY_PAGE_KEY = 'page_key';

    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * @return string|null
     */
    public function getCmsPageSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @project Only needed in nonsplit projects.
     *
     * @phpstan-param array<string, string> $data
     *
     * @phpstan-return array<string, string>
     *
     * @param string[] $data
     *
     * @return string[]
     */
    protected function getProjectSearchResultData(array $data): array
    {
        return [
            static::KEY_UUID => $data[static::KEY_UUID],
            static::KEY_VALID_TO => $this->getValidTo($data[static::KEY_VALID_TO]),
            static::KEY_IS_SEARCHABLE => $data[static::KEY_IS_SEARCHABLE],
            static::KEY_PAGE_KEY => $data[static::KEY_PAGE_KEY],
        ];
    }

    /**
     * @param string|null $validToValue
     *
     * @return string|null
     */
    protected function getValidTo(?string $validToValue): ?string
    {
        return isset($validToValue) ? (new DateTime($validToValue))->format(static::DATE_FORMAT) : null;
    }
}
