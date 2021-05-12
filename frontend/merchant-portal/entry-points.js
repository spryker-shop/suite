const path = require("path");
const {
    ROOT_SPRYKER_CORE_DIR,
    MP_CORE_ENTRY_POINT_FILE,
    ROOT_SPRYKER_PROJECT_DIR,
    MP_PROJECT_ENTRY_POINT_FILE,
} = require("./mp-paths");
const { getMPEntryPoints, entryPointPathToName } = require("./utils");

async function getMPEntryPointsMap() {
    const entryPointsMap = async (dir, entryPath) => {
        const entryPoints = await getMPEntryPoints(dir, entryPath);

        return entryPoints.reduce(
            (acc, entryPoint) => ({
                ...acc,
                [entryPointPathToName("spy/", entryPoint)]: path.join(
                    dir,
                    entryPoint
                )
            }),
            {}
        );
    };
    const coreEntryPoints = await entryPointsMap(ROOT_SPRYKER_CORE_DIR, MP_CORE_ENTRY_POINT_FILE);
    const projectEntryPoints = await entryPointsMap(ROOT_SPRYKER_PROJECT_DIR, MP_PROJECT_ENTRY_POINT_FILE);

    return {
        ...coreEntryPoints,
        ...projectEntryPoints,
    };
}

module.exports = {
    getMPEntryPoints,
    getMPEntryPointsMap,
};
