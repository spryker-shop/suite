const path = require('path');
const glob = require('fast-glob');
const appSettings = require('../settings');

// define the default glob settings for fast-glob
const defaultGlobSettings = {
    followSymlinkedDirectories: false,
    absolute: true,
    onlyFiles: true,
    onlyDirectories: false
}

// perform a search in a list of directories
// matching provided patterns
// using provided glob settings
async function globAsync(patterns, rootConfiguration) {
    try {
        return await glob(patterns, rootConfiguration);
    } catch(error) {
        console.error('An error occurred while globbing the system for entry points.', error);
    }
}

async function find(globDirs, globPatterns, globSettings = {}) {
    return await globDirs.reduce(async (resultsPromise, dir) => {
        const rootConfiguration = {
            ...defaultGlobSettings,
            ...globSettings,
            cwd: dir
        };

        const results = await resultsPromise;
        const globPath = await globAsync(globPatterns, rootConfiguration);

        return results.concat(globPath);
    }, Promise.resolve([]));
}

// find components according to `appSettings.find.componentEntryPoints`
async function findComponentEntryPoints() {
    const settings = appSettings.find.componentEntryPoints;
    const files = await find(settings.dirs, settings.patterns, settings.globSettings);

    const entryPoints = Object.values(files.reduce((map, file) => {
        const dir = path.dirname(file);
        const name = path.basename(dir);
        const type = path.basename(path.dirname(dir));
        map[`${type}/${name}`] = file;
        return map;
    }, {}));

    console.log(`Component entry points: ${entryPoints.length} found`);
    return entryPoints;
}

// find styles according to `appSettings.find.componentStyles`
async function findComponentStyles() {
    const settings = appSettings.find.componentStyles;
    const styles = await find(settings.dirs, settings.patterns, settings.globSettings);

    console.log(`Component styles: ${styles.length} found`);
    return styles;
}

module.exports = {
    findComponentEntryPoints,
    findComponentStyles
}
