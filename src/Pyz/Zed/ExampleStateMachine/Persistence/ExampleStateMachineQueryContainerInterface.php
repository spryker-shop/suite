<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ExampleStateMachine\Persistence;

use Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery;
use Propel\Runtime\Collection\Collection;

interface ExampleStateMachineQueryContainerInterface
{
    /**
     * @psalm-suppress TooManyTemplateParams
     *
     * @param array<int> $stateIds
     *
     * @return \Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery<\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem>
     */
    public function queryStateMachineItemsByStateIds(array $stateIds = []): PyzExampleStateMachineItemQuery;

    /**
     * @psalm-suppress TooManyTemplateParams
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem>
     */
    public function queryAllStateMachineItems(): Collection;

    /**
     * @psalm-suppress TooManyTemplateParams
     *
     * @param int $idStateMachineItem
     *
     * @return \Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery<\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem>
     */
    public function queryExampleStateMachineItemByIdStateMachineItem(
        $idStateMachineItem,
    ): PyzExampleStateMachineItemQuery;
}
