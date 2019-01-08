const configPromise = require('./development');
const merge = require('webpack-merge');

async function configurationWatchMode() {
    const config = await configPromise();

    return merge(config, {
        watch: true
    })
}

module.exports = configurationWatchMode;
