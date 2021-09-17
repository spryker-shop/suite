<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage\Business\Storage\Cte;

use Pyz\Zed\PriceProductStorage\Business\Exception\CteNotFoundException;
use Pyz\Zed\PriceProductStorage\Business\Exception\CteNotSupportedException;
use Pyz\Zed\Propel\Business\PropelFacadeInterface;

class PriceProductStorageCteStrategyResolver implements PriceProductStorageCteStrategyResolverInterface
{
    /**
     * @var array<\Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface>
     */
    protected $cteCollection;

    /**
     * @var \Pyz\Zed\Propel\Business\PropelFacadeInterface
     */
    protected $propelFacade;

    /**
     * @param array<\Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface> $cteCollection
     * @param \Pyz\Zed\Propel\Business\PropelFacadeInterface $propelFacade
     */
    public function __construct(array $cteCollection, PropelFacadeInterface $propelFacade)
    {
        $this->cteCollection = $cteCollection;
        $this->propelFacade = $propelFacade;
    }

    /**
     * @throws \Pyz\Zed\PriceProductStorage\Business\Exception\CteNotFoundException
     *
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface
     */
    public function resolve(): PriceProductStorageCteInterface
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
     * @throws \Pyz\Zed\PriceProductStorage\Business\Exception\CteNotSupportedException
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
