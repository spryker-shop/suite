const getAttributes = require('./command-line-parser');
const { getFilteredNamespaceConfigList } = require('./namespace-config-parser');
const { getAppSettings } = require('./../settings');
const imagesOptimization = require('../libs/images-optimization');

const requestedArguments = getAttributes();
const namespaceConfigList = getFilteredNamespaceConfigList(requestedArguments);
const appSettings = getAppSettings(namespaceConfigList, requestedArguments.pathToConfig);

appSettings.forEach(item => {
    console.info('Image compression has been started...');
    imagesOptimization(item);
});
