const fs = require('fs');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const getCopyConfig = appSettings => {
    const ignoredArray = ['*.gitkeep'];

    const currentMode = process.argv.slice(2)[0];
    Object.keys(appSettings.imageOptimizationOptions.enableModes).map(mode => {
        const isOptimisationEnabled = currentMode === mode && appSettings.imageOptimizationOptions.enableModes[mode];
        if (isOptimisationEnabled) {
            ignoredArray.push('**/images/**');
        }
    });

    return Object.values(appSettings.paths.assets).reduce((copyConfig, assetsPath) => {
        if (fs.existsSync(assetsPath)) {
            copyConfig.push({
                from: assetsPath,
                to: '.',
                ignore: ignoredArray,
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
