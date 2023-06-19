const glob = require("fast-glob");
const { strings } = require("@angular-devkit/core");

async function getMPEntryPoints(directory, entryPath) {
    return glob(entryPath, {
        cwd: directory,
    });
}

function entryPointPathToName(prefix, path) {
    return prefix + strings.dasherize(path.split("/")[0]);
}

module.exports = {
    getMPEntryPoints,
    entryPointPathToName,
};
