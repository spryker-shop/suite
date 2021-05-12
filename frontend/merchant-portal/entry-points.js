const path = require("path");

const ROOT_DIR = path.resolve(__dirname, "../..");

const SPRYKER_CORE_DIR = path.join(ROOT_DIR, "vendor/spryker");

const MP_CORE_ENTRY_POINT_FILE =
    "*/src/Spryker/Zed/*/Presentation/Components/entry.ts";
const MP_PROJECT_ENTRY_POINT_FILE =
    "*/Presentation/Components/entry.ts";

async function getMPEntryPoints(directory, entryPath) {
    return glob(entryPath, {
        cwd: directory,
    });
}

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
