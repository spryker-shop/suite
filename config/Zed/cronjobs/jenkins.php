<?php

declare(strict_types = 1);

/*
 * Notes:
 *
 * - jobs[]['name'] must not contain spaces or any other characters, that have to be urlencode()'d
 * - jobs[]['role'] default value is 'admin'
 */

$logger = 'config/Zed/cronjobs/bin/loggable.sh '; // script for jenkins logging
/* ProductValidity */
$jobs[] = [
    'name' => 'check-product-validity',
    'command' => $logger . '$PHP_BIN vendor/bin/console product:check-validity',
    'schedule' => '0 6 * * *',
    'enable' => true,
];

/* ProductLabel */
$jobs[] = [
    'name' => 'check-product-label-validity',
    'command' => $logger . '$PHP_BIN vendor/bin/console product-label:validity',
    'schedule' => '0 6 * * *',
    'enable' => true,
];
$jobs[] = [
    'name' => 'update-product-label-relations',
    'command' => $logger . '$PHP_BIN vendor/bin/console product-label:relations:update -vvv --no-touch',
    'schedule' => '* * * * *',
    'enable' => true,
];

/* Oms */
$jobs[] = [
    'name' => 'check-oms-conditions',
    'command' => $logger . '$PHP_BIN vendor/bin/console oms:check-condition',
    'schedule' => '* * * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'check-oms-timeouts',
    'command' => $logger . '$PHP_BIN vendor/bin/console oms:check-timeout',
    'schedule' => '* * * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'clear-oms-locks',
    'command' => $logger . '$PHP_BIN vendor/bin/console oms:clear-locks',
    'schedule' => '0 6 * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'queue-worker-start',
    'command' => $logger . '$PHP_BIN vendor/bin/console queue:worker:start',
    'schedule' => '* * * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'product-relation-updater',
    'command' => $logger . '$PHP_BIN vendor/bin/console product-relation:update -vvv',
    'schedule' => '30 2 * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'event-trigger-timeout',
    'command' => $logger . '$PHP_BIN vendor/bin/console event:trigger:timeout -vvv',
    'schedule' => '*/5 * * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'deactivate-discontinued-products',
    'command' => $logger . '$PHP_BIN vendor/bin/console deactivate-discontinued-products',
    'schedule' => '0 0 * * *',
    'enable' => true,
];

/* StateMachine */
/*
$jobs[] = [
    'name' => 'check-state-machine-conditions',
    'command' => '$PHP_BIN vendor/bin/console state-machine:check-condition',
    'schedule' => '* * * * *',
    'enable' => true,
    'stores' => $allStores,
];

$jobs[] = [
    'name' => 'check-state-machine-timeouts',
    'command' => '$PHP_BIN vendor/bin/console state-machine:check-timeout',
    'schedule' => '* * * * *',
    'enable' => true,
    'stores' => $allStores,
];

$jobs[] = [
    'name' => 'clear-state-machine-locks',
    'command' => '$PHP_BIN vendor/bin/console state-machine:clear-locks',
    'schedule' => '0 6 * * *',
    'enable' => true,
    'stores' => $allStores,
];
*/

/* Quote */
$jobs[] = [
    'name' => 'clean-expired-guest-cart',
    'command' => $logger . '$PHP_BIN vendor/bin/console quote:delete-expired-guest-quotes',
    'schedule' => '30 1 * * *',
    'enable' => true,
];

/* QuoteRequest */
$jobs[] = [
    'name' => 'close-outdated-quote-requests',
    'command' => $logger . '$PHP_BIN vendor/bin/console quote-request:close-outdated',
    'schedule' => '0 * * * *',
    'enable' => true,
];

/* PriceProductSchedule */
$jobs[] = [
    'name' => 'apply-price-product-schedule',
    'command' => $logger . '$PHP_BIN vendor/bin/console price-product-schedule:apply',
    'schedule' => '0 6 * * *',
    'enable' => true,
];

/* ProductOfferValidity */
$jobs[] = [
    'name' => 'check-product-offer-validity',
    'command' => $logger . '$PHP_BIN vendor/bin/console product-offer:check-validity',
    'schedule' => '0 6 * * *',
    'enable' => true,
];

/* Oauth */
$jobs[] = [
    'name' => 'remove-expired-refresh-tokens',
    'command' => $logger . '$PHP_BIN vendor/bin/console oauth:refresh-token:remove-expired',
    'schedule' => '*/5 * * * *',
    'enable' => true,
];

/* Customer */
$jobs[] = [
    'name' => 'delete-expired-customer-invalidated',
    'command' => $logger . '$PHP_BIN vendor/bin/console customer:delete-expired-customer-invalidated',
    'schedule' => '0 0 * * 0',
    'enable' => true,
];

/* Order invoice */
$jobs[] = [
    'name' => 'order-invoice-send',
    'command' => $logger . '$PHP_BIN vendor/bin/console order:invoice:send',
    'schedule' => '*/5 * * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'page-product-abstract-refresh',
    'command' => $logger . '$PHP_BIN vendor/bin/console product-page-search:product-abstract-refresh',
    'schedule' => '0 6 * * *',
    'enable' => true,
];

/* Push notification */
$jobs[] = [
    'name' => 'delete-expired-push-notification-subscriptions',
    'command' => $logger . '$PHP_BIN vendor/bin/console push-notification:delete-expired-push-notification-subscriptions',
    'schedule' => '0 0 * * 0',
    'enable' => true,
];

$jobs[] = [
    'name' => 'send-push-notifications',
    'command' => $logger . '$PHP_BIN vendor/bin/console push-notification:send-push-notifications',
    'schedule' => '* * * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'glue-api-generate-documentation',
    'command' => $logger . '$PHP_BIN vendor/bin/glue api:generate:documentation --invalidated-after-interval 90sec',
    'schedule' => '*/1 * * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'sync-order-matrix',
    'command' => $logger . '$PHP_BIN vendor/bin/console order-matrix:sync',
    'schedule' => '*/1 * * * *',
    'enable' => true,
    'global' => true,
];

$jobs[] = [
    'name' => 'generate-sitemap-files',
    'command' => '$PHP_BIN vendor/bin/console sitemap:generate',
    'schedule' => '0 0 * * *',
    'enable' => true,
];

$jobs[] = [
    'name' => 'merchant-portal-file-import',
    'command' => $logger . '$PHP_BIN vendor/bin/console merchant-portal:file-import',
    'schedule' => '* * * * *',
    'enable' => true,
];

/* Message broker */
if (\Spryker\Shared\Config\Config::get(\Spryker\Shared\MessageBroker\MessageBrokerConstants::IS_ENABLED)) {
    $jobs[] = [
        'name' => 'message-broker-consume-channels',
        'command' => $logger . '$PHP_BIN vendor/bin/console message-broker:consume --time-limit=15 --sleep=5',
        'schedule' => '* * * * *',
        'enable' => true,
    ];
}

if (getenv('SPRYKER_CURRENT_REGION')) {
    foreach ($jobs as $job) {
        $job['region'] = getenv('SPRYKER_CURRENT_REGION');
    }
}
