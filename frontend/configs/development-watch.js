const config = require('./development');

async function configurationWatchMode() {
    return await config.then(configResult => {
        return {
            ...configResult,
            watch: true
        }
    });
}

module.exports = configurationWatchMode()
