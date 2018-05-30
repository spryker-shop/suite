<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DemoDataGenerator\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorBusinessFactory;
use Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorFacade;
use Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorFacadeInterface;
use Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig;

abstract class AbstractCsvGenerator extends Unit
{
    /**
     * @var \PyzTest\Zed\DemoDataGenerator\DemoDataGeneratorTester
     */
    protected $tester;

    /**
     * @param string $type
     * @param string|null $rows
     *
     * @return \Generated\Shared\Transfer\DemoDataGeneratorTransfer
     */
    protected function createDemoDataGeneratorTransfer(string $type, ?string $rows = null): DemoDataGeneratorTransfer
    {
        $demoDataGeneratorTransfer = new DemoDataGeneratorTransfer();
        $demoDataGeneratorTransfer->setType($type);
        $demoDataGeneratorTransfer->setRowNumber($rows);

        return $demoDataGeneratorTransfer;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $demoDataGeneratorFactoryMock
     *
     * @return \Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorFacadeInterface
     */
    protected function createDemoDataGeneratorFacade(MockObject $demoDataGeneratorFactoryMock): DemoDataGeneratorFacadeInterface
    {
        $demoDataGeneratorFacade = new DemoDataGeneratorFacade();
        $demoDataGeneratorFacade->setFactory($demoDataGeneratorFactoryMock);

        return $demoDataGeneratorFacade;
    }

    /**
     * @param string $method
     * @param string $path
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createDemoDataConfigMock(string $method, string $path): MockObject
    {
        $demoDataGeneratorConfigMock = $this->getMockBuilder(DemoDataGeneratorConfig::class)
            ->setMethods([$method])
            ->getMock();

        $demoDataGeneratorConfigMock
            ->method($method)
            ->willReturn(codecept_data_dir() . $path);

        return $demoDataGeneratorConfigMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $demoDataGeneratorConfigMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createDemoDataGeneratorFactoryMock(MockObject $demoDataGeneratorConfigMock): MockObject
    {
        $demoDataGeneratorFactoryMock = $this->getMockBuilder(DemoDataGeneratorBusinessFactory::class)
            ->setMethods(['getConfig'])
            ->getMock();

        $demoDataGeneratorFactoryMock
            ->method('getConfig')
            ->willReturn($demoDataGeneratorConfigMock);

        return $demoDataGeneratorFactoryMock;
    }
}
