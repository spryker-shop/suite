// get mode, array of namespaces and array of themes for build
const requestedArguments = require('./libs/command-line-parcer');

// get store manager
const { getFilteredConfigNamespaces } = require('./libs/store-manager');

// get the settings manager
const { getAppSettings } = require('./settings');

// get the webpack compiler
const compiler = require('./libs/compiler');

// get the webpack configuration associated with the provided mode
const getConfiguration = require(`./configs/${requestedArguments.mode}`);

// get array of namespace config
const configNamespaces = getFilteredConfigNamespaces(requestedArguments);

// get the promise for each store webpack configuration
const configurationPromises = getAppSettings(configNamespaces)
    .map(getConfiguration);

// clear all assets
compiler.clearAllAssets(requestedArguments.namespaces, requestedArguments.themes);

// build the project
Promise.all(configurationPromises)
    .then(configs => compiler.multiCompile(configs))
    .catch(error => console.error('An error occur while creating configuration', error));
