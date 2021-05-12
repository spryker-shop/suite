const fs = require("fs");
const path = require("path");
const { spawnSync } = require("child_process");
const {
    SPRYKER_CORE_DIR,
    MP_CORE_ENTRY_POINT_FILE,
    MP_PUBLIC_API_FILE,
    ROOT_DIR,
} = require("./mp-paths");
const { getMPEntryPoints, entryPointPathToName } = require("./utils");

const TSCONFIG_FILES = ["tsconfig.json", "tsconfig.mp.json"];

async function getMPPathsMap() {
    const entryPoints = await getMPEntryPoints(SPRYKER_CORE_DIR, MP_CORE_ENTRY_POINT_FILE);

    return entryPoints.reduce(
        (acc, entryPoint) => ({
            ...acc,
            [entryPointPathToName("@mp/", entryPoint)]: [
                path.join(
                    SPRYKER_CORE_DIR,
                    entryPoint.split("/src")[0],
                    MP_PUBLIC_API_FILE,
                )
            ]
        }),
        {}
    );
}

async function updateConfigPaths() {
    const mpPaths = await  getMPPathsMap();

    for (let i = 0; i < TSCONFIG_FILES.length; i++) {
        const fileName = TSCONFIG_FILES[i];
        const config = require(path.join(ROOT_DIR, fileName));

        config.compilerOptions.paths = {
            ...mpPaths,
            ...config.compilerOptions.paths,
        };

        fs.writeFileSync(fileName, JSON.stringify(config));

        spawnSync(
            'node',
            ['./frontend/libs/formatter', '-f', '-p', fileName],
            { stdio: 'inherit', cwd: ROOT_DIR },
        );
    }
}

updateConfigPaths().catch(error => {
    console.error(error);
    process.exit(1);
});
