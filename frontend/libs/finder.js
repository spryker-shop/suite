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
async function find(globDirs, globPatterns, globSettings = {}) {
    return await globDirs.reduce(async (results, dir) => {
        const globAsync = glob(globPatterns, {
            defaultGlobSettings,
            ...globSettings,
            cwd: dir
        });

        return await globAsync.then(globResult => {
            const globFullPath = globResult.map(result => path.join(dir, result));

            return results.then(result => {
                return [
                    ...result,
                    ...globFullPath
                ];
            }, error => {
                log.error(error, 'impossible to resolve fast-glob promise');
            })
        })
    }, Promise.resolve([]));
}

// find components according to `appSettings.find.componentEntryPoints`
async function findComponentEntryPoints() {
    console.log('Scanning for component entry points...');
    const settings = appSettings.find.componentEntryPoints;
    const filesPromise = find(settings.dirs, settings.patterns, settings.globSettings);

    return await filesPromise.then(files => {
        const entryPoints = Object.values(files.reduce((map, file) => {
            const dir = path.dirname(file);
            const name = path.basename(dir);
            const type = path.basename(path.dirname(dir));
            map[`${type}/${name}`] = file;
            return map;
        }, {}));

        console.log(`${entryPoints.length} found`);
        return entryPoints;
    }, error => {
        log.error(error, 'impossible to resolve entries promise');
    })
}

// find styles according to `appSettings.find.componentStyles`
async function findComponentStyles() {
    console.log('Scanning for component styles...');
    const settings = appSettings.find.componentStyles;
    const stylesPromise = find(settings.dirs, settings.patterns, settings.globSettings);

    return await stylesPromise.then(styles => {
        console.log(`${styles.length} found`);
        return styles;
    }, error => {
        log.error(error, 'impossible to resolve styles promise');
    })
}

module.exports = {
    findComponentEntryPoints,
    findComponentStyles
}
