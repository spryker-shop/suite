const glob = require('fast-glob');

function dasherize(str) {
    return str
        .replace(/[\s_]/g, '-')
        .replace(/([a-z])([A-Z])/g, '$1-$2')
        .toLowerCase();
}

async function getMPEntryPoints(directory, entryPath) {
    return glob(entryPath, {
        cwd: directory,
    });
}

function entryPointPathToName(prefix, path) {
    return prefix + dasherize(path.split('/')[0]);
}

module.exports = {
    getMPEntryPoints,
    entryPointPathToName,
};
