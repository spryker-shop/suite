<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\PropelFacadeInterface;
use Pyz\Zed\UrlStorage\Business\Exception\CteNotFoundException;
use Pyz\Zed\UrlStorage\Business\Exception\CteNotSupportedException;

class UrlStorageCteStrategyResolver implements UrlStorageCteStrategyResolverInterface
{
    /**
     * @var \Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface[]
     */
    protected $cteCollection;

    /**
     * @var \Pyz\Zed\Propel\Business\PropelFacadeInterface
     */
    protected $propelFacade;

    /**
     * @param \Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface[] $cteCollection
     * @param \Pyz\Zed\Propel\Business\PropelFacadeInterface $propelFacade
     */
    public function __construct(array $cteCollection, PropelFacadeInterface $propelFacade)
    {
        $this->cteCollection = $cteCollection;
        $this->propelFacade = $propelFacade;
    }

    /**
     * @throws \Pyz\Zed\UrlStorage\Business\Exception\CteNotFoundException
     *
     * @return \Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface
     */
    public function resolve(): UrlStorageCteInterface
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
     * @throws \Pyz\Zed\UrlStorage\Business\Exception\CteNotSupportedException
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
