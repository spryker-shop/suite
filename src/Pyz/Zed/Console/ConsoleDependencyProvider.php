<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Console;

use Pyz\Zed\DataImport\DataImportConfig;
use Silex\Provider\TwigServiceProvider as SilexTwigServiceProvider;
use Spryker\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportConfig;
use Spryker\Zed\Cache\Communication\Console\EmptyAllCachesConsole;
use Spryker\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportConfig;
use Spryker\Zed\CompanyDataImport\CompanyDataImportConfig;
use Spryker\Zed\CompanyUnitAddressDataImport\CompanyUnitAddressDataImportConfig;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\CompanyUnitAddressLabelDataImportConfig;
use Spryker\Zed\Console\ConsoleDependencyProvider as SprykerConsoleDependencyProvider;
use Spryker\Zed\Console\Dependency\Resolver\OptionalCommandResolver;
use Spryker\Zed\DataImport\Communication\Console\DataImportConsole;
use Spryker\Zed\EventBehavior\Communication\Console\EventBehaviorTriggerTimeoutConsole;
use Spryker\Zed\EventBehavior\Communication\Console\EventTriggerConsole;
use Spryker\Zed\EventBehavior\Communication\Plugin\Console\EventBehaviorPostHookPlugin;
use Spryker\Zed\Installer\Communication\Console\InitializeDatabaseConsole;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Log\Communication\Console\DeleteLogFilesConsole;
use Spryker\Zed\Maintenance\Communication\Console\MaintenanceDisableConsole;
use Spryker\Zed\Maintenance\Communication\Console\MaintenanceEnableConsole;
use Spryker\Zed\Money\Communication\Plugin\ServiceProvider\TwigMoneyServiceProvider;
use Spryker\Zed\MultiCartDataImport\MultiCartDataImportConfig;
use Spryker\Zed\Oms\Communication\Console\CheckConditionConsole as OmsCheckConditionConsole;
use Spryker\Zed\Oms\Communication\Console\CheckTimeoutConsole as OmsCheckTimeoutConsole;
use Spryker\Zed\Oms\Communication\Console\ClearLocksConsole as OmsClearLocksConsole;
use Spryker\Zed\PriceProduct\Communication\Console\PriceProductStoreOptimizeConsole;
use Spryker\Zed\PriceProductDataImport\PriceProductDataImportConfig;
use Spryker\Zed\PriceProductMerchantRelationship\Communication\Console\PriceProductMerchantRelationshipDeleteConsole;
use Spryker\Zed\PriceProductSchedule\Communication\Console\PriceProductScheduleApplyConsole;
use Spryker\Zed\PriceProductSchedule\Communication\Console\PriceProductScheduleCleanupConsole;
use Spryker\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportConfig;
use Spryker\Zed\ProductAlternativeDataImport\ProductAlternativeDataImportConfig;
use Spryker\Zed\ProductDiscontinued\Communication\Console\DeactivateDiscontinuedProductsConsole;
use Spryker\Zed\ProductDiscontinuedDataImport\ProductDiscontinuedDataImportConfig;
use Spryker\Zed\ProductLabel\Communication\Console\ProductLabelRelationUpdaterConsole;
use Spryker\Zed\ProductLabel\Communication\Console\ProductLabelValidityConsole;
use Spryker\Zed\ProductPackagingUnitDataImport\ProductPackagingUnitDataImportConfig;
use Spryker\Zed\ProductRelation\Communication\Console\ProductRelationUpdaterConsole;
use Spryker\Zed\ProductValidity\Communication\Console\ProductValidityConsole;
use Spryker\Zed\Propel\Communication\Console\DatabaseDropConsole;
use Spryker\Zed\Propel\Communication\Console\DatabaseExportConsole;
use Spryker\Zed\Propel\Communication\Console\DatabaseImportConsole;
use Spryker\Zed\Propel\Communication\Console\DeleteMigrationFilesConsole;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Spryker\Zed\Queue\Communication\Console\QueueTaskConsole;
use Spryker\Zed\Queue\Communication\Console\QueueWorkerConsole;
use Spryker\Zed\Quote\Communication\Console\DeleteExpiredGuestQuoteConsole;
use Spryker\Zed\QuoteRequest\Communication\Console\CloseOutdatedQuoteRequestConsole;
use Spryker\Zed\RabbitMq\Communication\Console\DeleteAllExchangesConsole;
use Spryker\Zed\RabbitMq\Communication\Console\DeleteAllQueuesConsole;
use Spryker\Zed\RabbitMq\Communication\Console\PurgeAllQueuesConsole;
use Spryker\Zed\RabbitMq\Communication\Console\SetUserPermissionsConsole;
use Spryker\Zed\RestRequestValidator\Communication\Console\BuildValidationCacheConsole;
use Spryker\Zed\Search\Communication\Console\GenerateIndexMapConsole;
use Spryker\Zed\Search\Communication\Console\SearchCloseIndexConsole;
use Spryker\Zed\Search\Communication\Console\SearchConsole;
use Spryker\Zed\Search\Communication\Console\SearchCopyIndexConsole;
use Spryker\Zed\Search\Communication\Console\SearchCreateSnapshotConsole;
use Spryker\Zed\Search\Communication\Console\SearchDeleteIndexConsole;
use Spryker\Zed\Search\Communication\Console\SearchDeleteSnapshotConsole;
use Spryker\Zed\Search\Communication\Console\SearchOpenIndexConsole;
use Spryker\Zed\Search\Communication\Console\SearchRegisterSnapshotRepositoryConsole;
use Spryker\Zed\Search\Communication\Console\SearchRestoreSnapshotConsole;
use Spryker\Zed\Session\Communication\Console\SessionRemoveLockConsole;
use Spryker\Zed\Setup\Communication\Console\DeployPreparePropelConsole;
use Spryker\Zed\Setup\Communication\Console\EmptyGeneratedDirectoryConsole;
use Spryker\Zed\Setup\Communication\Console\InstallConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsDisableConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsEnableConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsGenerateConsole;
use Spryker\Zed\Setup\Communication\Console\Npm\RunnerConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\CleanUpDependenciesConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\InstallPackageManagerConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\InstallProjectDependenciesConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\YvesBuildFrontendConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\YvesInstallDependenciesConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\ZedBuildFrontendConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\ZedInstallDependenciesConsole;
use Spryker\Zed\SharedCartDataImport\SharedCartDataImportConfig;
use Spryker\Zed\ShoppingListDataImport\ShoppingListDataImportConfig;
use Spryker\Zed\StateMachine\Communication\Console\CheckConditionConsole as StateMachineCheckConditionConsole;
use Spryker\Zed\StateMachine\Communication\Console\CheckTimeoutConsole as StateMachineCheckTimeoutConsole;
use Spryker\Zed\StateMachine\Communication\Console\ClearLocksConsole as StateMachineClearLocksConsole;
use Spryker\Zed\Storage\Communication\Console\StorageDeleteAllConsole;
use Spryker\Zed\Storage\Communication\Console\StorageExportRdbConsole;
use Spryker\Zed\Storage\Communication\Console\StorageImportRdbConsole;
use Spryker\Zed\Synchronization\Communication\Console\ExportSynchronizedDataConsole;
use Spryker\Zed\Transfer\Communication\Console\GeneratorConsole;
use Spryker\Zed\Translator\Communication\Console\CleanTranslationCacheConsole;
use Spryker\Zed\Translator\Communication\Console\GenerateTranslationCacheConsole;
use Spryker\Zed\Twig\Communication\Console\CacheWarmerConsole;
use Spryker\Zed\Twig\Communication\Plugin\ServiceProvider\TwigServiceProvider as SprykerTwigServiceProvider;
use Spryker\Zed\Uuid\Communication\Console\UuidGeneratorConsole;
use Spryker\Zed\ZedNavigation\Communication\Console\BuildNavigationConsole;

/**
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @method \Pyz\Zed\Console\ConsoleConfig getConfig()
 */
class ConsoleDependencyProvider extends SprykerConsoleDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getConsoleCommands(Container $container)
    {
        $commands = [
            new CacheWarmerConsole(),
            new BuildNavigationConsole(),
            new BuildValidationCacheConsole(),
            new EmptyAllCachesConsole(),
            new GeneratorConsole(),
            new InitializeDatabaseConsole(),
            new SearchConsole(),
            new GenerateIndexMapConsole(),
            new OmsCheckConditionConsole(),
            new OmsCheckTimeoutConsole(),
            new OmsClearLocksConsole(),
            new StateMachineCheckTimeoutConsole(),
            new StateMachineCheckConditionConsole(),
            new StateMachineClearLocksConsole(),
            new SessionRemoveLockConsole(),
            new QueueTaskConsole(),
            new QueueWorkerConsole(),
            new ProductRelationUpdaterConsole(),
            new ProductLabelValidityConsole(),
            new ProductLabelRelationUpdaterConsole(),
            new ProductValidityConsole(),
            new DataImportConsole(),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_STORE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_CURRENCY),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_CATEGORY_TEMPLATE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_CUSTOMER),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_GLOSSARY),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_NAVIGATION),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_NAVIGATION_NODE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_CMS_TEMPLATE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_CMS_BLOCK),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_CMS_BLOCK_STORE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_CMS_BLOCK_CATEGORY_POSITION),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_CMS_BLOCK_CATEGORY),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_DISCOUNT),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_DISCOUNT_STORE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_DISCOUNT_VOUCHER),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_ABSTRACT),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_ABSTRACT_STORE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_CONCRETE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_IMAGE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_STOCK),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_ATTRIBUTE_KEY),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_MANAGEMENT_ATTRIBUTE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_GROUP),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_OPTION),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_OPTION_PRICE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_RELATION),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_REVIEW),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_LABEL),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_SET),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE_MAP),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_SHIPMENT),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_SHIPMENT_PRICE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_STOCK),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_TAX),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_DISCOUNT_AMOUNT),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_ORDER_SOURCE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_ABSTRACT_GIFT_CARD_CONFIGURATION), #GiftCardFeature
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . DataImportConfig::IMPORT_TYPE_CONCRETE_GIFT_CARD_CONFIGURATION), #GiftCardFeature

            //core data importers
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . PriceProductDataImportConfig::IMPORT_TYPE_PRODUCT_PRICE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . PriceProductScheduleDataImportConfig::IMPORT_TYPE_PRODUCT_PRICE_SCHEDULE),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . CompanyDataImportConfig::IMPORT_TYPE_COMPANY),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . CompanyBusinessUnitDataImportConfig::IMPORT_TYPE_COMPANY_BUSINESS_UNIT),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . CompanyUnitAddressDataImportConfig::IMPORT_TYPE_COMPANY_UNIT_ADDRESS),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . CompanyUnitAddressLabelDataImportConfig::IMPORT_TYPE_COMPANY_UNIT_ADDRESS_LABEL),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . CompanyUnitAddressLabelDataImportConfig::IMPORT_TYPE_COMPANY_UNIT_ADDRESS_LABEL_RELATION),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . ProductAlternativeDataImportConfig::IMPORT_TYPE_PRODUCT_ALTERNATIVE), #ProductAlternativeFeature
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . BusinessOnBehalfDataImportConfig::IMPORT_TYPE_COMPANY_USER),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . ProductDiscontinuedDataImportConfig::IMPORT_TYPE_PRODUCT_DISCONTINUED), #ProductDiscontinuedFeature
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . MultiCartDataImportConfig::IMPORT_TYPE_MULTI_CART),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . SharedCartDataImportConfig::IMPORT_TYPE_SHARED_CART),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . ProductPackagingUnitDataImportConfig::IMPORT_TYPE_PRODUCT_PACKAGING_UNIT),

            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_ITEM),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_COMPANY_USER),
            new DataImportConsole(DataImportConsole::DEFAULT_NAME . ':' . ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_COMPANY_BUSINESS_UNIT),

            // Publish and Synchronization
            new EventBehaviorTriggerTimeoutConsole(),
            new EventTriggerConsole(),
            new ExportSynchronizedDataConsole(),

            // Setup commands
            new RunnerConsole(),
            new EmptyGeneratedDirectoryConsole(),
            new InstallConsole(),
            new JenkinsEnableConsole(),
            new JenkinsDisableConsole(),
            new JenkinsGenerateConsole(),
            new DeployPreparePropelConsole(),

            new DatabaseDropConsole(),

            new DatabaseExportConsole(),
            new DatabaseImportConsole(),
            new DeleteMigrationFilesConsole(),

            new DeleteLogFilesConsole(),
            new StorageExportRdbConsole(),
            new StorageImportRdbConsole(),
            new StorageDeleteAllConsole(),
            new SearchDeleteIndexConsole(),
            new SearchCloseIndexConsole(),
            new SearchOpenIndexConsole(),
            new SearchRegisterSnapshotRepositoryConsole(),
            new SearchDeleteSnapshotConsole(),
            new SearchCreateSnapshotConsole(),
            new SearchRestoreSnapshotConsole(),
            new SearchCopyIndexConsole(),

            new InstallPackageManagerConsole(),
            new CleanUpDependenciesConsole(),
            new InstallProjectDependenciesConsole(),

            new YvesInstallDependenciesConsole(),
            new YvesBuildFrontendConsole(),

            new ZedInstallDependenciesConsole(),
            new ZedBuildFrontendConsole(),

            new DeleteAllQueuesConsole(),
            new PurgeAllQueuesConsole(),
            new DeleteAllExchangesConsole(),
            new SetUserPermissionsConsole(),

            new MaintenanceEnableConsole(),
            new MaintenanceDisableConsole(),

            new DeactivateDiscontinuedProductsConsole(), #ProductDiscontinuedFeature

            new PriceProductStoreOptimizeConsole(),
            new PriceProductMerchantRelationshipDeleteConsole(),

            new DeleteExpiredGuestQuoteConsole(),
            new UuidGeneratorConsole(),

            new CleanTranslationCacheConsole(),
            new GenerateTranslationCacheConsole(),
            new CloseOutdatedQuoteRequestConsole(),

            new PriceProductScheduleApplyConsole(),
            new PriceProductScheduleCleanupConsole(),
        ];

        $propelCommands = $container->getLocator()->propel()->facade()->getConsoleCommands();
        $commands = array_merge($commands, $propelCommands);

        return $commands;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Console\Dependency\Resolver\OptionalCommandResolverInterface[]
     */
    protected function getOptionalConsoleResolvers(Container $container)
    {
        $resolvers = [];

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\CodeGenerator\Communication\Console\BundleClientCodeGeneratorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\CodeGenerator\Communication\Console\BundleCodeGeneratorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\CodeGenerator\Communication\Console\BundleServiceCodeGeneratorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\CodeGenerator\Communication\Console\BundleSharedCodeGeneratorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\CodeGenerator\Communication\Console\BundleYvesCodeGeneratorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\CodeGenerator\Communication\Console\BundleZedCodeGeneratorConsole');

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\DataImport\Communication\Console\DataImportDumpConsole');

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\CodeArchitectureSnifferConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\CodePhpMessDetectorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\CodePhpstanConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\CodeTestConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\ComposerJsonUpdaterConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\GenerateClientIdeAutoCompletionConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\GenerateGlueIdeAutoCompletionConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\GenerateIdeAutoCompletionConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\GenerateServiceIdeAutoCompletionConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\GenerateYvesIdeAutoCompletionConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\GenerateZedIdeAutoCompletionConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\ModuleBridgeCreateConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\ModuleCreateConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\PluginUsageFinderConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\PropelAbstractValidateConsole');

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\DocumentationGeneratorRestApi\Communication\Console\GenerateRestApiDocumentationConsole');

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\EventBehavior\Communication\Console\EventTriggerListenerConsole');

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\IndexGenerator\Communication\Console\PostgresIndexGeneratorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\IndexGenerator\Communication\Console\PostgresIndexRemoverConsole');

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Propel\Communication\Console\PropelSchemaValidatorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Propel\Communication\Console\PropelSchemaXmlNameValidatorConsole');

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Queue\Communication\Console\QueueDumpConsole');

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Transfer\Communication\Console\DataBuilderGeneratorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Transfer\Communication\Console\ValidatorConsole');

        $resolvers[] = new OptionalCommandResolver('\Stecman\Component\Symfony\Console\BashCompletion\CompletionCommand;');

        $resolvers = $this->addProjectNonSplitOnlyResolvers($resolvers);

        return $resolvers;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    public function getConsolePostRunHookPlugins(Container $container)
    {
        return [
            new EventBehaviorPostHookPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    public function getServiceProviders(Container $container)
    {
        $serviceProviders = parent::getServiceProviders($container);
        $serviceProviders[] = new PropelServiceProvider();
        $serviceProviders[] = new SilexTwigServiceProvider();
        $serviceProviders[] = new SprykerTwigServiceProvider();
        $serviceProviders[] = new TwigMoneyServiceProvider();

        return $serviceProviders;
    }

    /**
     * @project Only available in internal nonsplit project, not in public split project.
     *
     * @param array $resolvers
     *
     * @return array
     */
    protected function addProjectNonSplitOnlyResolvers(array $resolvers): array
    {
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\ComposerJsonValidatorConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\DependencyTreeBuilderConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\DependencyViolationFinderConsole');
        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\Development\Communication\Console\DependencyViolationFixConsole');

        $resolvers[] = new OptionalCommandResolver('\Spryker\Zed\DevelopmentCore\Communication\Console\AdjustPhpstanConsole');

        $resolvers[] = new OptionalCommandResolver('\SprykerSdk\Spryk\Console\SprykBuildConsole');
        $resolvers[] = new OptionalCommandResolver('\SprykerSdk\Spryk\Console\SprykDumpConsole');
        $resolvers[] = new OptionalCommandResolver('\SprykerSdk\Spryk\Console\SprykRunConsole');

        return $resolvers;
    }
}
