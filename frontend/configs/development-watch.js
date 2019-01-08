const getConfiguration = require('./development');
const merge = require('webpack-merge');

async function configurationWatchMode() {
    const config = await getConfiguration();

    return merge(config, {
        watch: true
    })
}

module.exports = configurationWatchMode;
