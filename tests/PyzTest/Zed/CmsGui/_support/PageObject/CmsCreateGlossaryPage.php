<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\CmsGui\PageObject;

class CmsCreateGlossaryPage
{
    /**
     * @var string
     */
    public const URL = 'cms-gui/create-glossary/index?id-cms-page=%d';

    /**
     * @var array
     */
    protected static $localizedPlaceholders = [
        'title' => [
            'en' => 'english title',
            'de' => 'german title',
        ],
        'contents' => [
            'en' => 'english contents',
            'de' => 'german contents',
        ],
    ];

    /**
     * @param string $placeholder
     * @param string $locale
     *
     * @return string
     */
    public static function getLocalizedPlaceholderData($placeholder, $locale): string
    {
        return static::$localizedPlaceholders[$placeholder][$locale];
    }
}
