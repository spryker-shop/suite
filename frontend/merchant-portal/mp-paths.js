const path = require("path");

const ROOT_DIR = path.resolve(__dirname, "../..");

const SPRYKER_CORE_DIR = "vendor/spryker";
const SPRYKER_PROJECT_DIR = "src/Pyz/Zed";

const ROOT_SPRYKER_CORE_DIR = path.join(ROOT_DIR, SPRYKER_CORE_DIR);
const ROOT_SPRYKER_PROJECT_DIR = path.join(ROOT_DIR, SPRYKER_PROJECT_DIR);

const MP_CORE_ENTRY_POINT_FILE =
    "*/src/Spryker/Zed/*/Presentation/Components/entry.ts";
const MP_PROJECT_ENTRY_POINT_FILE =
    "*/Presentation/Components/entry.ts";

const MP_PUBLIC_API_FILE = 'mp.public-api.ts';

module.exports = {
    ROOT_DIR,
    SPRYKER_CORE_DIR,
    SPRYKER_PROJECT_DIR,
    ROOT_SPRYKER_CORE_DIR,
    ROOT_SPRYKER_PROJECT_DIR,
    MP_CORE_ENTRY_POINT_FILE,
    MP_PROJECT_ENTRY_POINT_FILE,
    MP_PUBLIC_API_FILE,
};
