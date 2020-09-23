<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Development\Business\CodeStyleSniffer;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Development
 * @group Business
 * @group CodeStyleSniffer
 * @group CodeStyleSnifferTest
 * Add your own group annotations below this line
 */
class CodeStyleSnifferTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * The list of default CodeStyleSniffer options.
     *
     * @var array
     */
    protected $defaultOptions = [
        'module' => null,
        'sniffs' => null,
        'level' => 1,
        'explain' => false,
        'dry-run' => false,
        'fix' => false,
        'help' => false,
        'quiet' => false,
        'verbose' => false,
        'version' => false,
        'ansi' => false,
        'no-ansi' => false,
        'no-interaction' => false,
        'no-pre' => false,
        'no-post' => false,
        'path' => null,
    ];

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInProject(): void
    {
        $options = ['ignore' => 'vendor/'] + $this->defaultOptions;
        $pathToApplicationRoot = APPLICATION_ROOT_DIR . '/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($pathToApplicationRoot, $options);

        $codeStyleSnifferMock->checkCodeStyle(null, $options);
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInProjectModule(): void
    {
        $options = ['ignore' => 'vendor/'] + $this->defaultOptions;
        $pathToApplicationRoot = rtrim(APPLICATION_ROOT_DIR) . '/src/Pyz/Zed/Development/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($pathToApplicationRoot, $options);

        $codeStyleSnifferMock->checkCodeStyle('Development', $options);
    }

    /**
     * @param string $expectedPathToRunCommandWith
     * @param array $options
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer
     */
    protected function getCodeStyleSnifferMock(string $expectedPathToRunCommandWith, array $options): CodeStyleSniffer
    {
        $developmentConfig = $this->tester->getModuleConfig();
        $codingStandard = $developmentConfig->getCodingStandard();

        if ($options['level'] === 2) {
            /** @see \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfiguration::getCodingStandard() */
            $codingStandard = APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR . 'spryker/code-sniffer/SprykerStrict/ruleset.xml';
        }

        $codeStyleSnifferMock = $this
            ->getMockBuilder(CodeStyleSniffer::class)
            ->setConstructorArgs([
                $developmentConfig,
                $this->tester->getFactory()->createCodeStyleSnifferConfigurationLoader(),
            ])
            ->setMethods(['runSnifferCommand'])
            ->getMock();

        $codeStyleSnifferMock
            ->method('runSnifferCommand')
            ->with(
                $expectedPathToRunCommandWith,
                $this->callback(function ($subject) use ($codingStandard) {
                    return is_callable([$subject, 'getCodingStandard']) && $subject->getCodingStandard() === $codingStandard;
                })
            );

        return $codeStyleSnifferMock;
    }
}
