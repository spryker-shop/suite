<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\GlossaryStorage\Communication\Plugin\Publisher;

use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryKey\GlossaryDeletePublisherPlugin;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryKey\GlossaryWritePublisherPlugin;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryTranslation\GlossaryWritePublisherPlugin as GlossaryTranslationWritePublisherPlugin;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface;
use Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface;

/**
 * @method \Spryker\Zed\GlossaryStorage\Communication\GlossaryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 */
class GlossaryStoragePublisherRegistryPlugin extends AbstractPlugin implements PublisherRegistryPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface
     */
    public function expandPublisherEventRegistry(PublisherEventRegistryInterface $publisherEventRegistry): PublisherEventRegistryInterface
    {
        $publisherEventRegistry->register(GlossaryEvents::GLOSSARY_KEY_PUBLISH, GlossaryWritePublisherPlugin::class);
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_CREATE, GlossaryWritePublisherPlugin::class);
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_UPDATE, GlossaryWritePublisherPlugin::class);
        $publisherEventRegistry->register(GlossaryEvents::GLOSSARY_KEY_UNPUBLISH, GlossaryDeletePublisherPlugin::class);
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_DELETE, GlossaryDeletePublisherPlugin::class);
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE, GlossaryTranslationWritePublisherPlugin::class);
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE, GlossaryTranslationWritePublisherPlugin::class);

        return $publisherEventRegistry;
    }
}
