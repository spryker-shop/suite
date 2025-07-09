<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\VolumeDataGeneration\Communication\Console;

use Pyz\Zed\VolumeDataGeneration\VolumeDataGenerationConfig;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\VolumeDataGeneration\Communication\VolumeDataGenerationCommunicationFactory getFactory()
 */
class VolumeDataGeneratorConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'volume-data:generate';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Generates volume data for Ssp entities.';

    /**
     * @var string
     */
    protected const OPTION_ENTITY_TYPE = 'entity-type';

    /**
     * @var string
     */
    protected const OPTION_ENTITY_TYPE_SHORT = 'e';

    /**
     * @var string
     */
    protected const OPTION_ALL = 'all';

    /**
     * @var string
     */
    protected const OPTION_ALL_SHORT = 'a';

    /**
     * @var string
     */
    protected const CODECEPTION_CONFIG_PATH = '/tests/VolumeDataGenerationTest/Zed/Ssp/codeception.yml';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption(
                static::OPTION_ENTITY_TYPE,
                static::OPTION_ENTITY_TYPE_SHORT,
                InputOption::VALUE_REQUIRED,
                sprintf('Entity type to generate (%s)', implode(', ', $this->getFactory()->getConfig()->getEntityTypes())),
            )
            ->addOption(
                static::OPTION_ALL,
                static::OPTION_ALL_SHORT,
                InputOption::VALUE_NONE,
                'Generate all entity types',
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->buildCodeception();

        if ($input->getOption(static::OPTION_ALL)) {
            foreach ($this->getFactory()->getConfig()->getEntityTypes() as $entityType) {
                $output->writeln(sprintf('<info>Generating data for entity type: %s</info>', $entityType));
                $this->generateEntities($entityType, $output);
            }

            return static::CODE_SUCCESS;
        }

        $entityType = $input->getOption(static::OPTION_ENTITY_TYPE);
        if (!$entityType) {
            $output->writeln('<error>Please specify an entity type or use --all option.</error>');

            return static::CODE_ERROR;
        }

        if (!in_array($entityType, $this->getFactory()->getConfig()->getEntityTypes(), true)) {
            $output->writeln(sprintf('<error>Invalid entity type: %s</error>', $entityType));
            $output->writeln(sprintf('<info>Available entity types: %s</info>', implode(', ', $this->getFactory()->getConfig()->getEntityTypes())));

            return static::CODE_ERROR;
        }

        $this->generateEntities($entityType, $output);

        return static::CODE_SUCCESS;
    }

    /**
     * @return void
     */
    protected function buildCodeception(): void
    {
        $command = 'vendor/bin/codecept build -c ' . $this->getCodeceptionConfigPath();
        exec($command . ' 2>&1');
    }

    /**
     * @param string $entityType
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function generateEntities(string $entityType, OutputInterface $output): void
    {
        $startTime = microtime(true);
        $totalMemory = 0;

        $currentIteration = 0;
        $command = 'ITERATION=%s vendor/bin/codecept run -c ' . $this->getCodeceptionConfigPath() . ' -g %s';

        $allDataIsGenerated = false;

        while (!$allDataIsGenerated) {
            $iterationStart = microtime(true);
            $output->writeln(sprintf('<info>[Iteration %d Starting...]</info>', $currentIteration + 1));

            $currentCommand = sprintf($command, $currentIteration, $entityType);
            $output->writeln(sprintf('Running: %s', $currentCommand));

            $commandOutput = [];
            exec($currentCommand . ' 2>&1', $commandOutput);

            $commandOutputAsString = implode("\n", $commandOutput);

            if (strpos($commandOutputAsString, 'ERRORS!') !== false) {
                $output->write($commandOutput);
                exit(static::CODE_ERROR);
            }

            if (preg_match('/Memory: ([0-9.]+) MB/', $commandOutputAsString, $matches)) {
                $memoryUsage = (float)$matches[1];
                $totalMemory += $memoryUsage;
            }

            if (strpos($commandOutputAsString, VolumeDataGenerationConfig::ALL_ENTITIES_GENERATED_MESSAGE) !== false || strpos($commandOutputAsString, VolumeDataGenerationConfig::NO_TESTS_EXECUTED) !== false) {
                $allDataIsGenerated = true;
                $output->writeln('<info>Detected completion signal. Stopping iterations.</info>');
            }

            if (strpos($commandOutputAsString, VolumeDataGenerationConfig::GENERATION_RESULT_TEXT) !== false) {
                foreach ($commandOutput as $line) {
                    if (strpos($line, VolumeDataGenerationConfig::GENERATION_RESULT_TEXT) !== false) {
                        $output->writeln(sprintf('<info>%s</info>', $line));

                        break;
                    }
                }
            }

            $iterationTime = microtime(true) - $iterationStart;
            $output->writeln(sprintf(
                '<info>[Iteration %d] Completed in %.2f seconds</info>',
                $currentIteration + 1,
                $iterationTime,
            ));

            gc_collect_cycles();

            $output->writeln('<info>-------------------------------------------------------</info>');

            $currentIteration++;
        }

        $totalTime = microtime(true) - $startTime;

        $output->writeln('<info>All iterations completed!</info>');
        $output->writeln(sprintf('Total execution time: %.2f seconds', $totalTime));
        $output->writeln(sprintf('Memory usage: %.2f MB', $totalMemory));
    }

    /**
     * @return string
     */
    protected function getCodeceptionConfigPath(): string
    {
        return APPLICATION_ROOT_DIR . static::CODECEPTION_CONFIG_PATH;
    }
}
