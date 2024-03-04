<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business\Storage\Cte;

use Pyz\Zed\ProductStorage\Business\Exception\CteNotFoundException;
use Pyz\Zed\ProductStorage\Business\Exception\CteNotSupportedException;
use Pyz\Zed\Propel\Business\PropelFacadeInterface;

class ProductStorageCteStrategyResolver implements ProductStorageCteStrategyResolverInterface
{
    /**
     * @var array<\Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface>
     */
    protected $cteCollection;

    /**
     * @var \Pyz\Zed\Propel\Business\PropelFacadeInterface
     */
    protected $propelFacade;

    /**
     * @param array<\Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface> $cteCollection
     * @param \Pyz\Zed\Propel\Business\PropelFacadeInterface $propelFacade
     */
    public function __construct(array $cteCollection, PropelFacadeInterface $propelFacade)
    {
        $this->cteCollection = $cteCollection;
        $this->propelFacade = $propelFacade;
    }

    /**
     * @throws \Pyz\Zed\ProductStorage\Business\Exception\CteNotFoundException
     *
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function resolve(): ProductStorageCteStrategyInterface
    {
        $this->checkCteSupport();
        $currentDatabaseEngine = $this->propelFacade->getCurrentDatabaseEngine();

        foreach ($this->cteCollection as $cte) {
            if (in_array($currentDatabaseEngine, $cte->getCompatibleEngines(), true)) {
                return $cte;
            }
        }

        throw new CteNotFoundException('Common table expression strategy not found.');
    }

    /**
     * @throws \Pyz\Zed\ProductStorage\Business\Exception\CteNotSupportedException
     *
     * @return void
     */
    protected function checkCteSupport(): void
    {
        if (!$this->propelFacade->checkCteSupport()) {
            throw new CteNotSupportedException('Common table expressions are not supported by your RDBMS.');
        }
    }
}
