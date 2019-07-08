// get arguments from command line (mode, namespace list, theme list, info about namespaces and path to config JSON file
const requestedArguments = require('./libs/command-line-parcer');

// get namespace config filter
const { getFilteredNamespaceConfigList } = require('./libs/namespace-manager');

// get the settings manager
const { getAppSettings } = require('./settings');

// get the webpack compiler
const compiler = require('./libs/compiler');

// get the webpack configuration associated with the provided mode
const getConfiguration = require(`./configs/${requestedArguments.mode}`);

// clear all assets
compiler.clearAllAssets(requestedArguments.namespaces, requestedArguments.themes);

// get array of filtered namespace config
const NamespaceConfigList = getFilteredNamespaceConfigList(requestedArguments);

// get the promise for each namespace webpack configuration
const configurationPromises = getAppSettings(NamespaceConfigList)
    .map(getConfiguration);

// build the project
Promise.all(configurationPromises)
    .then(configs => compiler.multiCompile(configs))
    .catch(error => console.error('An error occur while creating configuration', error));
