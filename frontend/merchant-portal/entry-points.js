const { strings } = require("@angular-devkit/core");
const glob = require("fast-glob");
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
                [entryPointPathToName(entryPoint)]: path.join(
                    dir,
                    entryPoint
                ),
            }),
            {}
        );
    };
    const coreEntryPoints = await entryPointsMap(SPRYKER_CORE_DIR, MP_CORE_ENTRY_POINT_FILE);
    const projectEntryPoints = await entryPointsMap(SPRYKER_PROJECT_DIR, MP_PROJECT_ENTRY_POINT_FILE);

    return {
        ...coreEntryPoints,
        ...projectEntryPoints,
    };
}

function entryPointPathToName(path) {
    return 'spy/' + strings.dasherize(path.split("/")[0]);
}

module.exports = {
    getMPEntryPoints,
    getMPEntryPointsMap,
};
