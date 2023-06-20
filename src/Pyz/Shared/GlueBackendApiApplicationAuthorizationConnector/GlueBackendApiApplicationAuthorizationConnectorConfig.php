<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\GlueBackendApiApplicationAuthorizationConnector;

use Spryker\Shared\GlueBackendApiApplicationAuthorizationConnector\GlueBackendApiApplicationAuthorizationConnectorConfig as SprykerGlueBackendApiApplicationAuthorizationConnectorConfig;

class GlueBackendApiApplicationAuthorizationConnectorConfig extends SprykerGlueBackendApiApplicationAuthorizationConnectorConfig
{
    /**
     * Specification:
     * - Returns a list of protected endpoints.
     * - Structure example:
     * [
     *      '/example' => [
     *          'isRegularExpression' => false,
     *      ],
     *      '/\/example\/.+/' => [
     *          'isRegularExpression' => true,
     *          'methods' => [
     *              'patch',
     *              'delete',
     *          ],
     *      ],
     * ]
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getProtectedPaths(): array
    {
        return [
            '/categories' => [
                'isRegularExpression' => false,
            ],
            '#^/categories/.*#' => [
                'isRegularExpression' => true,
            ],
            '/\/product-attributes.*/' => [
                'isRegularExpression' => true,
                'methods' => [
                    'get',
                    'getCollection',
                    'post',
                    'patch',
                ],
            ],
            '/\/product-abstract.*/' => [
                'isRegularExpression' => true,
                'methods' => [
                    'get',
                    'getCollection',
                    'post',
                    'patch',
                ],
            ],
            '/\/warehouse-user-assignments(?:\/[^\/]+)?\/?$/' => [
                'isRegularExpression' => true,
            ],
            '/push-notification-subscriptions' => [
                'isRegularExpression' => false,
            ],
            '/warehouse-tokens' => [
                'isRegularExpression' => false,
                'methods' => [
                    'post',
                ],
            ],
            '/\/picking-lists.*/' => [
                'isRegularExpression' => true,
                'methods' => [
                    'patch',
                ],
            ],
            '/\/service-points.*/' => [
                'isRegularExpression' => true,
            ],
            '/\/shipment-types.*/' => [
                'isRegularExpression' => true,
            ],
            '/\/services.*/' => [
                'isRegularExpression' => true,
            ],
            '/\/service-types.*/' => [
                'isRegularExpression' => true,
            ],
        ];
    }
}
