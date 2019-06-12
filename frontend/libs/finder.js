const path = require('path');
const glob = require('fast-glob');

// define the default glob settings for fast-glob
const defaultGlobSettings = {
    followSymlinkedDirectories: false,
    absolute: true,
    onlyFiles: true,
    onlyDirectories: false
};

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

function findFiles(globDirs, globPatterns, globSettings) {
    return globDirs.reduce(async (resultsPromise, dir) => {
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

async function find(globDirs, globPatterns, globFallbackPatterns, globSettings = {}) {
    const customThemeFiles = await findFiles(globDirs, globPatterns, globSettings);
    let defaultThemeFiles = [];

    if (globFallbackPatterns.length > 0) {
        defaultThemeFiles = await findFiles(globDirs, globFallbackPatterns, globSettings);
    }

    return defaultThemeFiles.concat(customThemeFiles);
}

// find components entry points
async function findEntryPoints(settings) {
    const files = await find(settings.dirs, settings.patterns,  settings.fallbackPatterns, settings.globSettings);

    const entryPoints = Object.values(files.reduce((map, file) => {
        const dir = path.dirname(file);
        const name = path.basename(dir);
        const type = path.basename(path.dirname(dir));
        map[`${type}/${name}`] = file;
        return map;
    }, {}));

    console.log(`Components entry points: ${entryPoints.length}`);

    return entryPoints;
}

// find component styles
async function findStyles(settings) {
    const styles = await find(settings.dirs, settings.patterns, [], settings.globSettings);

    console.log(`Components styles: ${styles.length}`);
    return styles;
}

async function findAppEntryPointPromise(settings, file) {
    let config = Object.assign({}, settings);
    const updatePatterns = function(patterncollection) {
        return patterncollection.map((pattern) => {
            return path.join(pattern, file);
        });
    };

    config.patterns = updatePatterns(config.patterns);
    config.fallbackPatterns = updatePatterns(config.fallbackPatterns);

    const entryPoint = await findEntryPoints(config);
    return entryPoint[0];
}

module.exports = {
    findEntryPoints,
    findStyles,
    findAppEntryPointPromise
};
