const getAttributes = require('./command-line-parser');
const { getFilteredNamespaceConfigList } = require('./namespace-config-parser');
const { getAppSettings } = require('./../settings');
const imagesOptimization = require('../libs/images-optimization');

const { join } = require('path');
const { existsSync } = require('fs');
const rimraf = require('rimraf');

const requestedArguments = getAttributes();
const namespaceConfigList = getFilteredNamespaceConfigList(requestedArguments);
const appSettingsList = getAppSettings(namespaceConfigList, requestedArguments.pathToConfig);

let isGlobalImagesRemoved = false;

const replaceImages = appSettings => {
    Object.values(appSettings.paths.assets).map(async assetsPath => {
        const optimizedImagesPath = join(assetsPath, '/images/optimized-images/');
        const isGlobalImages = assetsPath === appSettings.paths.assets.globalAssets;

        if (!existsSync(optimizedImagesPath) || isGlobalImages && isGlobalImagesRemoved) {
            return;
        }

        rimraf(optimizedImagesPath, () => console.info('Optimized images was successfully removed'));

        if (isGlobalImages) {
            isGlobalImagesRemoved = true;
        }
    });
};

appSettingsList.forEach(appSettings => {
    if (requestedArguments.replaceOptimizedImages) {
        replaceImages(appSettings);
        return;
    }

    console.info('Image compression has been started...');
    imagesOptimization(appSettings);
});
