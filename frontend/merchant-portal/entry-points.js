const { strings } = require("@angular-devkit/core");
const glob = require("fast-glob");
const path = require("path");

const ROOT_DIR = path.resolve(__dirname, "../..");

const SPRYKER_CORE_DIR = path.join(ROOT_DIR, "vendor/spryker");

const MP_ENTRY_POINT_FILE =
    "*/src/Spryker/Zed/*/Presentation/Components/index.ts";

async function getMPEntryPoints() {
    return glob(MP_ENTRY_POINT_FILE, {
        cwd: SPRYKER_CORE_DIR,
    });
}

async function getMPEntryPointsMap() {
    const entryPoints = await getMPEntryPoints();

    return entryPoints.reduce(
        (acc, entryPoint) => ({
            ...acc,
            [entryPointPathToName(entryPoint)]: path.join(
                SPRYKER_CORE_DIR,
                entryPoint
            ),
        }),
        {}
    );
}

function entryPointPathToName(path) {
    return 'spy/' + strings.dasherize(path.split("/")[0]);
}

module.exports = {
    getMPEntryPoints,
    getMPEntryPointsMap,
};
