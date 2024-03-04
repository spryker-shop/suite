<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\Glossary;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConfig;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class GlossaryWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var int
     */
    public const BULK_SIZE = 100;

    /**
     * @var string
     */
    public const KEY_KEY = 'key';

    /**
     * @var string
     */
    public const KEY_TRANSLATION = 'translation';

    /**
     * @var string
     */
    public const KEY_ID_LOCALE = 'idLocale';

    /**
     * @var string
     */
    public const KEY_LOCALE = 'locale';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $glossaryKeyEntity = SpyGlossaryKeyQuery::create()
            ->filterByKey($dataSet[static::KEY_KEY])
            ->findOneOrCreate();

        $glossaryKeyEntity->save();

        $glossaryTranslationEntity = SpyGlossaryTranslationQuery::create()
            ->filterByGlossaryKey($glossaryKeyEntity)
            ->filterByFkLocale($dataSet[static::KEY_ID_LOCALE])
            ->findOneOrCreate();

        $glossaryTranslationEntity
            ->setValue($dataSet[static::KEY_TRANSLATION])
            ->save();

        $this->addPublishEvents(GlossaryStorageConfig::GLOSSARY_KEY_PUBLISH_WRITE, $glossaryTranslationEntity->getFkGlossaryKey());
    }
}
