const { join } = require('path');

// define the current context (root)
const context = process.cwd();

function getAppSettingsByStore(store) {
    // define current theme
    const currentTheme = store.currentTheme || store.defaultTheme;

    // define the applicatin name
    const name = `${store.name}_${currentTheme}`;

    // define relative urls to site host (/)
    const urls = {
        // assets base url
        defaultAssets: join('/assets', store.name, store.defaultTheme),
        currentAssets: join('/assets', store.name, currentTheme)
    };

    const assetPaths = function() {
        let assetPathsCollection = {
            // global assets folder
            globalAssets: './frontend/assets/global',

            // assets folder for current theme in store
            defaultAssets: join('./frontend', urls.defaultAssets),
        };

        if (currentTheme !== store.defaultTheme) {
            // assets folder for current theme in store
            assetPathsCollection.currntAssets = join('./frontend', urls.currentAssets);
        }

        return assetPathsCollection
    };

    // define project relative paths to context
    const paths = {
        // locate the typescript configuration json file
        tsConfig: './tsconfig.json',

        assets: assetPaths(),

        // public folder
        public: join('./public/Yves', urls.currentAssets),

        // core folders
        core: {
            // all modules
            modules: './vendor/spryker/spryker-shop/Bundles',
            // ShopUi source folder
            shopUiModule: `./vendor/spryker/spryker-shop/Bundles/ShopUi/src/SprykerShop/Yves/ShopUi/Theme/${store.defaultTheme}`
        },

        // eco folders
        eco: {
            // all modules
            modules: './vendor/spryker-eco'
        },

        // project folders
        project: {
            // all modules
            modules: './src/Pyz/Yves',
            // ShopUi source folder
            shopUiModule: `./src/Pyz/Yves/ShopUi/Theme/${store.defaultTheme}`
        }
    };

    // define entry point patterns for current theme, if current theme is defined
    let customThemeEntryPointPatterns = [];
    if (currentTheme !== store.defaultTheme) {
        customThemeEntryPointPatterns = [
            `**/Theme/${currentTheme}/components/atoms/*/index.ts`,
            `**/Theme/${currentTheme}/components/molecules/*/index.ts`,
            `**/Theme/${currentTheme}/components/organisms/*/index.ts`,
            `**/Theme/${currentTheme}/templates/*/index.ts`,
            `**/Theme/${currentTheme}/views/*/index.ts`,
            `**/*${store.name}/Theme/${currentTheme}/components/atoms/*/index.ts`,
            `**/*${store.name}/Theme/${currentTheme}/components/molecules/*/index.ts`,
            `**/*${store.name}/Theme/${currentTheme}/components/organisms/*/index.ts`,
            `**/*${store.name}/Theme/${currentTheme}/templates/*/index.ts`,
            `**/*${store.name}/Theme/${currentTheme}/views/*/index.ts`,
            '!config',
            '!data',
            '!deploy',
            '!node_modules',
            '!public',
            '!test'
        ];

    }

    // return settings
    return {
        name,
        store,
        context,
        paths,
        urls,

        // define settings for suite-frontend-builder finder
        find: {
            // webpack entry points (components) finder settings
            componentEntryPoints: {
                // absolute dirs in which look for
                dirs: [
                    join(context, paths.core.modules),
                    join(context, paths.eco.modules),
                    join(context, paths.project.modules)
                ],
                // files/dirs patterns
                patterns: customThemeEntryPointPatterns,
                fallbackPatterns: [
                    `**/Theme/${store.defaultTheme}/components/atoms/*/index.ts`,
                    `**/Theme/${store.defaultTheme}/components/molecules/*/index.ts`,
                    `**/Theme/${store.defaultTheme}/components/organisms/*/index.ts`,
                    `**/Theme/${store.defaultTheme}/templates/*/index.ts`,
                    `**/Theme/${store.defaultTheme}/views/*/index.ts`,
                    `**/*${store.name}/Theme/${store.defaultTheme}/components/atoms/*/index.ts`,
                    `**/*${store.name}/Theme/${store.defaultTheme}/components/molecules/*/index.ts`,
                    `**/*${store.name}/Theme/${store.defaultTheme}/components/organisms/*/index.ts`,
                    `**/*${store.name}/Theme/${store.defaultTheme}/templates/*/index.ts`,
                    `**/*${store.name}/Theme/${store.defaultTheme}/views/*/index.ts`,
                    '!config',
                    '!data',
                    '!deploy',
                    '!node_modules',
                    '!public',
                    '!test'
                ]
            },

            // core component styles finder settings
            // important: this part is used in shared scss environment
            // do not change unless necessary
            componentStyles: {
                // absolute dirs in which look for
                dirs: [
                    join(context, paths.core.modules)
                ],
                // files/dirs patterns
                patterns: [
                    `**/Theme/${store.defaultTheme}/components/atoms/*/*.scss`,
                    `**/Theme/${store.defaultTheme}/components/molecules/*/*.scss`,
                    `**/Theme/${store.defaultTheme}/components/organisms/*/*.scss`,
                    `**/Theme/${store.defaultTheme}/templates/*/*.scss`,
                    `**/Theme/${store.defaultTheme}/views/*/*.scss`,
                    `!**/Theme/${store.defaultTheme}/**/style.scss`,
                    '!config',
                    '!data',
                    '!deploy',
                    '!node_modules',
                    '!public',
                    '!test'
                ]
            }
        }
    }
}

module.exports = {
    getAppSettingsByStore
}
