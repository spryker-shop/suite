const getConfiguration = require('./development');
const merge = require('webpack-merge');

async function configurationWatchMode(appSettings) {
    const config = await getConfiguration(appSettings);

    return merge(config, {
        watch: true
    })
}

module.exports = configurationWatchMode;
