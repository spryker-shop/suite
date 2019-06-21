const webpack = require('webpack');
const rimraf = require('rimraf');
const { globalSettings } = require('../settings');

// execute webpack compiler
// and nicely handle the console output
function compile(config, storeName) {
    console.log(`Building for ${config.mode}...`);

    if (config.watch) {
        console.log('Watch mode: ON');
    }

    webpack(config, (err, stats) => {
        if (err) {
            console.error(err.stack || err);

            if (err.details) {
                console.error(err.details);
            }

            return;
        }
        console.log(`${storeName} store:`);
        console.log(stats.toString(config.stats), '\n');
    });
}

// execute webpack compiler on array of configurations
// and nicely handle the console output
function multiCompile(configs) {
    if (configs.length === 0 || configs.length === undefined) {
        return console.error('No configuration provided. Build aborted.');
    }

    configs.forEach((config) => {
        console.log(`${config.storeName} building for ${config.webpack.mode}...`);

        if (config.webpack.watch) {
            console.log(`${config.storeName} watch mode: ON`);
        }
    });

    const webpackConfigs = configs.map(item => item.webpack);
    webpack(webpackConfigs, (err, multiStats) => {
        if (err) {
            console.error(err.stack || err);

            if (err.details) {
                console.error(err.details);
            }

            return;
        }

        multiStats.stats.forEach(
            (stat, index) => {
                console.log(`${configs[index].storeName} store:`);
                console.log(stat.toString(webpackConfigs[index].stats), '\n')
            }
        );
    });
}

// clear assets
function clearAllAssets(storeIds) {
    if (storeIds.length === 0) {
        rimraf(globalSettings.paths.publicAll, () => {
            console.log(`${globalSettings.paths.publicAll} has been removed. \n`);
        });
    }
}

module.exports = {
    compile,
    multiCompile,
    clearAllAssets
};
