<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Zed\AclEntity\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use ReflectionClass;
use ReflectionProperty;
use Spryker\Zed\Acl\Business\Model\Rule;
use Spryker\Zed\AclEntity\Business\Reader\AclEntityMetadataConfigReader;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProvider;

class StaticCacheHelper extends Module
{
    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $ruleCacheProperty = $this->getReflectionProperty(Rule::class, 'cache');
        $ruleCacheProperty->setValue([]);

        $groupsTransferCacheProperty = $this->getReflectionProperty(Rule::class, 'groupsTransferCache');
        $groupsTransferCacheProperty->setValue([]);

        $rulesTransferCacheProperty = $this->getReflectionProperty(Rule::class, 'rulesTransferCache');
        $rulesTransferCacheProperty->setValue([]);

        $cacheProperty = $this->getReflectionProperty(AclEntityMetadataConfigReader::class, 'cache');
        $cacheProperty->setValue([]);

        $cacheProperty = $this->getReflectionProperty(AclEntityRuleProvider::class, 'aclEntityRuleCollectionTransfer');
        $cacheProperty->setValue(null);
    }

    /**
     * @param string $class
     * @param string $property
     *
     * @return \ReflectionProperty
     */
    protected function getReflectionProperty(string $class, string $property): ReflectionProperty
    {
        $reflection = new ReflectionClass($class);
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty;
    }
}
