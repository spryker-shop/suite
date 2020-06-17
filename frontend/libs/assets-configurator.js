const fs = require('fs');
const { globalSettings } = require('../settings');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const getCopyConfig = appSettings => {
    const ignoredResources = ['*.gitkeep'];

    const currentMode = process.argv.slice(globalSettings.expectedModeArgument)[0];
    Object.keys(appSettings.imageOptimizationOptions.enabledInModes).map(mode => {
        const isOptimisationEnabled = currentMode === mode && appSettings.imageOptimizationOptions.enabledInModes[mode];
        if (isOptimisationEnabled) {
            ignoredResources.push('**/images/**');
        }
    });

    return Object.values(appSettings.paths.assets).reduce((copyConfig, assetsPath) => {
        if (fs.existsSync(assetsPath)) {
            copyConfig.push({
                from: assetsPath,
                to: '.',
                ignore: ignoredResources,
            });
        }
        return copyConfig;
    },[]);
};

const getAssetsConfig = appSettings => [
    new CleanWebpackPlugin([appSettings.paths.public],
        {
            root: appSettings.context,
            verbose: true,
            beforeEmit: true,
        }
    ),

    new CopyWebpackPlugin(getCopyConfig(appSettings), {
        context: appSettings.context,
    }),
];

module.exports = {
    getAssetsConfig
};
