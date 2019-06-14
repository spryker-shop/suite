const { join } = require('path');
const fs = require('fs');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const getCopyConfig = function(appSettings) {
    let config = [];

    for (let asset in appSettings.paths.assets) {
        if (fs.existsSync(appSettings.paths.assets[asset])) {
            config = config.concat([
                {
                    from: appSettings.paths.assets[asset],
                    to: '.',
                    ignore: ['*.gitkeep']
                }
            ]);
        }
    }

    return config;
};

function getAssetsConfig(appSettings) {
    return [
        new CleanWebpackPlugin([
            appSettings.paths.public
        ], {
            root: appSettings.context,
            verbose: true,
            beforeEmit: true
        }),

        new CopyWebpackPlugin(getCopyConfig(appSettings), {
            context: appSettings.context
        }),
    ];
}

module.exports = {
    getAssetsConfig
};
