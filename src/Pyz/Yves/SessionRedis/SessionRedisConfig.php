<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\SessionRedis;

use Spryker\Yves\SessionRedis\SessionRedisConfig as SprykerSessionRedisConfig;

/**
 * @method \Spryker\Shared\SessionRedis\SessionRedisConfig getSharedConfig()
 */
class SessionRedisConfig extends SprykerSessionRedisConfig
{
    /**
     * @return list<string>
     */
    public function getSessionRedisLockingExcludedUrlPatterns(): array
    {
        return [
            '/^.*\/error-page\/*.*$/',
            '/^.*\/health-check$/',
        ];
    }

    /**
     * @return list<string>
     */
    public function getSessionRedisLockingExcludedBotUserAgents(): array
    {
        return [
            'Googlebot',
            'bingbot',
            'Baiduspider',
            'YandexBot',
            'DuckDuckBot',
            'Sogou',
            'ia_archiver',
            'facebookexternalhit',
            'Twitterbot',
            'LinkedInBot',
            'Slackbot',
            'WhatsApp',
            'Discordbot',
            'AhrefsBot',
            'Applebot',
            'msnbot',
            'MJ12bot',
            'SEMrushBot',
            'PetalBot',
            'SeznamBot',
            'AdsBot-Google',
            'crawler',
            'spider',
            'robot',
            'bot/',
        ];
    }
}
