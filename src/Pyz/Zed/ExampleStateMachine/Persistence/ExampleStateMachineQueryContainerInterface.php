<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ExampleStateMachine\Persistence;

interface ExampleStateMachineQueryContainerInterface
{
    /**
     * @param array<int> $stateIds
     *
     * @return \Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery
     */
    public function queryStateMachineItemsByStateIds(array $stateIds = []);

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem[]
     */
    public function queryAllStateMachineItems();

    /**
     * @param int $idStateMachineItem
     *
     * @return \Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery
     */
    public function queryExampleStateMachineItemByIdStateMachineItem($idStateMachineItem);
}
