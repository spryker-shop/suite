const path = require('path');
const { readFileSync } = require('fs');
const {
    ROOT_SPRYKER_CORE_DIR,
    MP_CORE_ENTRY_POINT_FILE,
    ROOT_SPRYKER_PROJECT_DIR,
    MP_PROJECT_ENTRY_POINT_FILE,
} = require('./mp-paths');
const { getMPEntryPoints, entryPointPathToName } = require('./utils');
const MP_SINGLE_ENTRY = 'spy/merchant-portal';
const MP_SINGLE_ENTRY_MARKER = `${MP_SINGLE_ENTRY}:single-entry-marker`;

async function getMPEntryPointsMap() {
    const singleEntryNames = new Map();

    const entryPointsMap = async (dir, entryPath) => {
        const entryPoints = await getMPEntryPoints(dir, entryPath);

        return entryPoints.reduce((acc, entryPoint) => {
            const fullPath = path.join(dir, entryPoint);
            const isSingleEntry = readFileSync(fullPath, { encoding: 'utf8' }).includes(MP_SINGLE_ENTRY_MARKER);
            const name = entryPointPathToName('spy/', entryPoint);

            if (isSingleEntry || singleEntryNames.has(name)) {
                singleEntryNames.set(name, fullPath);

                return acc;
            }

            return { ...acc, [name]: fullPath };
        }, {});
    };
    const core = await entryPointsMap(ROOT_SPRYKER_CORE_DIR, MP_CORE_ENTRY_POINT_FILE);
    const project = await entryPointsMap(ROOT_SPRYKER_PROJECT_DIR, MP_PROJECT_ENTRY_POINT_FILE);

    return { ...core, ...project, [MP_SINGLE_ENTRY]: [...singleEntryNames.values()] };
}

module.exports = {
    getMPEntryPoints,
    getMPEntryPointsMap,
};
