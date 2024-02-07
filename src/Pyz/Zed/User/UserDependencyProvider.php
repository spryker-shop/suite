<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\User;

use Spryker\Zed\Acl\Communication\Plugin\GroupPlugin;
use Spryker\Zed\AgentGui\Communication\Plugin\UserAgentFormExpanderPlugin;
use Spryker\Zed\AgentGui\Communication\Plugin\UserAgentTableConfigExpanderPlugin;
use Spryker\Zed\AgentGui\Communication\Plugin\UserAgentTableDataExpanderPlugin;
use Spryker\Zed\CustomerUserConnectorGui\Communication\Plugin\UserTableActionExpanderPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantAgent\Communication\Plugin\User\MerchantAgentUserQueryCriteriaExpanderPlugin;
use Spryker\Zed\MerchantAgentGui\Communication\Plugin\User\MerchantAgentUserFormExpanderPlugin;
use Spryker\Zed\MerchantAgentGui\Communication\Plugin\User\MerchantAgentUserTableConfigExpanderPlugin;
use Spryker\Zed\MerchantAgentGui\Communication\Plugin\User\MerchantAgentUserTableDataExpanderPlugin;
use Spryker\Zed\PickingList\Communication\Plugin\User\UnassignPickingListUserPostSavePlugin;
use Spryker\Zed\User\UserDependencyProvider as SprykerUserDependencyProvider;
use Spryker\Zed\UserLocale\Communication\Plugin\User\AssignUserLocalePreSavePlugin;
use Spryker\Zed\UserLocale\Communication\Plugin\User\LocaleUserExpanderPlugin;
use Spryker\Zed\UserLocaleGui\Communication\Plugin\UserLocaleFormExpanderPlugin;
use Spryker\Zed\WarehouseUserGui\Communication\Plugin\User\WarehouseUserAssignmentUserFormExpanderPlugin;
use Spryker\Zed\WarehouseUserGui\Communication\Plugin\User\WarehouseUserAssignmentUserTableActionExpanderPlugin;

class UserDependencyProvider extends SprykerUserDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGroupPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_GROUP, function (Container $container) {
            return new GroupPlugin();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTableActionExpanderPluginInterface>
     */
    protected function getUserTableActionExpanderPlugins(): array
    {
        return [
            new UserTableActionExpanderPlugin(),
            new WarehouseUserAssignmentUserTableActionExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserFormExpanderPluginInterface>
     */
    protected function getUserFormExpanderPlugins(): array
    {
        return [
            new UserAgentFormExpanderPlugin(),
            new MerchantAgentUserFormExpanderPlugin(),
            new WarehouseUserAssignmentUserFormExpanderPlugin(),
            new UserLocaleFormExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface>
     */
    protected function getUserTableConfigExpanderPlugins(): array
    {
        return [
            new MerchantAgentUserTableConfigExpanderPlugin(),
            new UserAgentTableConfigExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface>
     */
    protected function getUserTableDataExpanderPlugins(): array
    {
        return [
            new UserAgentTableDataExpanderPlugin(),
            new MerchantAgentUserTableDataExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface>
     */
    protected function getUserPreSavePlugins(): array
    {
        return [
            new AssignUserLocalePreSavePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface>
     */
    protected function getUserExpanderPlugins(): array
    {
        return [
            new LocaleUserExpanderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface>
     */
    protected function getPostSavePlugins(): array
    {
        return [
            new UnassignPickingListUserPostSavePlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserQueryCriteriaExpanderPluginInterface>
     */
    protected function getUserQueryCriteriaExpanderPlugins(): array
    {
        return [
            new MerchantAgentUserQueryCriteriaExpanderPlugin(),
        ];
    }
}
