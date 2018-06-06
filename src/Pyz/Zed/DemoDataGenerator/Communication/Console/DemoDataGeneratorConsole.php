<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Communication\Console;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorFacadeInterface getFacade()
 */
class DemoDataGeneratorConsole extends Console
{
    protected const COMMAND_NAME = 'demo:data:generate';
    protected const DESCRIPTION = 'This will generate demo data in csv format';
    protected const ROWS_NUMBER_PARAMETER_DESCRIPTION = 'Amount of rows to be generated.';
    protected const TYPE_PARAMETER_DESCRIPTION = 'Entity type for demo data generation.';

    protected const PRODUCT_CONCRETE_TYPE = 'product_concrete';
    protected const PRODUCT_ABSTRACT_TYPE = 'product_abstract';
    protected const PRODUCT_PRICE_TYPE = 'product_price';
    protected const PRODUCT_ABSTRACT_STORE_TYPE = 'product_abstract_store';

    protected const TYPE_PARAMETER_NAME = 'type';
    protected const TYPE_PARAMETER_KEY = 't';
    protected const ROWS_NUMBER_PARAMETER_NAME = 'rows-number';
    protected const ROWS_NUMBER_PARAMETER_KEY = 'r';

    protected const ERROR_MESSAGE = 'Entity type for demo data generation is required';
    protected const FILE_PARAMETER_NAME = 'filePath';
    protected const FILE_PARAMETER_KEY = 'f';
    protected const FILE_PARAMETER_DESCRIPTION = 'File path for demo data generation';

    /**
     * @var int
     */
    protected $exitCode = self::CODE_SUCCESS;

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION)
            ->addOption(
                static::ROWS_NUMBER_PARAMETER_NAME,
                static::ROWS_NUMBER_PARAMETER_KEY,
                InputOption::VALUE_OPTIONAL,
                static::ROWS_NUMBER_PARAMETER_DESCRIPTION
            )
            ->addOption(
                static::TYPE_PARAMETER_NAME,
                static::TYPE_PARAMETER_KEY,
                InputOption::VALUE_REQUIRED,
                static::TYPE_PARAMETER_DESCRIPTION
            )->addOption(
                static::FILE_PARAMETER_NAME,
                static::FILE_PARAMETER_KEY,
                InputOption::VALUE_REQUIRED,
                static::FILE_PARAMETER_DESCRIPTION
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messenger = $this->getMessenger();
        $messenger->info(sprintf(
            'You just executed %s!',
            static::COMMAND_NAME
        ));

        $demoDataGeneratorTransfer = $this->getArguments($input, $output);

        if ($this->hasError()) {
            return $this->exitCode;
        }

        $this->getFacade()
            ->generate($demoDataGeneratorTransfer);

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Generated\Shared\Transfer\DemoDataGeneratorTransfer
     */
    protected function getArguments(InputInterface $input, OutputInterface $output): DemoDataGeneratorTransfer
    {
        $demoDataGeneratorTransfer = new DemoDataGeneratorTransfer();

        if ($input->getOption(static::TYPE_PARAMETER_NAME)) {
            $demoDataGeneratorTransfer->setType($input->getOption(static::TYPE_PARAMETER_NAME));
            $demoDataGeneratorTransfer->setRowNumber((int)strtolower($input->getOption(static::ROWS_NUMBER_PARAMETER_NAME)));
            $demoDataGeneratorTransfer->setFilePath($input->getOption(static::FILE_PARAMETER_NAME));

            return $demoDataGeneratorTransfer;
        }

        $this->exitCode = static::CODE_ERROR;
        $this->error(static::ERROR_MESSAGE);

        return $demoDataGeneratorTransfer;
    }

    /**
     * @return bool
     */
    protected function hasError(): bool
    {
        return $this->exitCode !== static::CODE_SUCCESS;
    }
}
