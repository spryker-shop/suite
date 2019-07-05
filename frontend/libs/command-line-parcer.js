const program = require('commander');
const { join } = require('path');
const { globalSettings } = require('../settings');

const parseToArray = (value) => {
    return value.split(',');
};

const getMode = (requestedMode) => {
    for (const mode in globalSettings.modes) {
        if (globalSettings.modes[mode] === requestedMode) {
            return requestedMode
        }
    }
    console.warn(`Mode "${program.mode}" is not available`);
    process.exit(1);
};

program
    .option('-m, --mode <mode>', 'set mode of build', getMode, globalSettings.modes.dev)
    .option('-n, --namespace <namespace list>', 'build the list of namespaces', parseToArray, [])
    .option('-t, --theme <theme list>', 'build the list of themes', parseToArray, [])
    .option('-i, --info', 'information about all namespaces and available themes')
    .option('-c, --config <path>', 'path to JSON file with namespace config', globalSettings.paths.namespaceConfig)
    .parse(process.argv);

const mode = program.mode;
const namespaces = program.namespace;
const themes = program.theme;
const info = program.info || false;
const pathToConfig = join(globalSettings.context, program.config);

module.exports = {
    mode,
    namespaces,
    themes,
    info,
    pathToConfig
};
