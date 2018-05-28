<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorFacade getFacade()
 */
class DemoDataGeneratorConsole extends Console
{
    const COMMAND_NAME = 'demo:data:generate';
    const DESCRIPTION = 'This will generate demo data in csv format';
    const ROWS_NUMBER_PARAMETER_DESCRIPTION = 'Amount of rows to be generated.';
    const TYPE_PARAMETER_DESCRIPTION = 'Entity type for demo data generation.';
    const PRODUCT_CONCRETE_TYPE = 'productconcrete';
    const PRODUCT_ABSTRACT_TYPE = 'productabstract';
    const TYPE_PARAMETER_NAME = 'type';
    const TYPE_PARAMETER_KEY = 't';
    const ROWS_NUMBER_PARAMETER_NAME = 'rows-number';
    const ROWS_NUMBER_PARAMETER_KEY = 'r';

    /**
     * @return void
     */
    protected function configure()
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
                InputOption::VALUE_OPTIONAL,
                static::TYPE_PARAMETER_DESCRIPTION
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

        $demoDataType = strtolower($input->getOption(static::TYPE_PARAMETER_NAME));
        $rowsNumber = (int)strtolower($input->getOption(static::ROWS_NUMBER_PARAMETER_NAME));

        switch ($demoDataType) {
            case static::PRODUCT_ABSTRACT_TYPE:
                $this->getFacade()->createProductAbstractCsvDemoData($rowsNumber);
                $this->getFacade()->createProductImageCsvDemoData($rowsNumber);
                break;

            case static::PRODUCT_CONCRETE_TYPE:
                $this->getFacade()->createProductConcreteCsvDemoData($rowsNumber);
                $this->getFacade()->createProductImageCsvDemoData($rowsNumber);
                break;

            default:
                $this->getFacade()->createProductAbstractCsvDemoData($rowsNumber);
                $this->getFacade()->createProductConcreteCsvDemoData($rowsNumber);
                $this->getFacade()->createProductImageCsvDemoData($rowsNumber);
                break;
        }

        return static::CODE_SUCCESS;
    }
}
