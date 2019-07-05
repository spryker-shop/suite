// get mode, array of namespaces and array of themes for build
const requestedArguments = require('./libs/command-line-parcer');

// get namespace manager
const { getFilteredNamespaceConfigList } = require('./libs/namespace-manager');

// get the settings manager
const { getAppSettings } = require('./settings');

// get the webpack compiler
const compiler = require('./libs/compiler');

// clear all assets
compiler.clearAllAssets(requestedArguments.namespaces, requestedArguments.themes);

// get the webpack configuration associated with the provided mode
const getConfiguration = require(`./configs/${requestedArguments.mode}`);

// get array of namespace config
const NamespaceConfigList = getFilteredNamespaceConfigList(requestedArguments);

// get the promise for each namespace webpack configuration
const configurationPromises = getAppSettings(NamespaceConfigList)
    .map(getConfiguration);

// build the project
Promise.all(configurationPromises)
    .then(configs => compiler.multiCompile(configs))
    .catch(error => console.error('An error occur while creating configuration', error));
