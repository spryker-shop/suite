const { join } = require('path');

// define global settings
const globalSettings = {
    // define the current context (root)
    context: process.cwd(),

    paths: {
        publicAll: './public/Yves/assets'
    }
};

function getAppSettingsByStore(store) {
    const entryPointsParts = [
        'components/atoms/*/index.ts',
        'components/molecules/*/index.ts',
        'components/organisms/*/index.ts',
        'templates/*/index.ts',
        'views/*/index.ts'
    ];

    const ignoreFiles = [
        '!config',
        '!data',
        '!deploy',
        '!node_modules',
        '!public',
        '!test'
    ];

    const entryPointsCollection = function(pathPattern) {
        return entryPointsParts.map((element) => `${pathPattern}/${element}`);
    };

    // define current theme
    const currentTheme = store.currentTheme || store.defaultTheme;

    // define the applicatin name
    // important: the name must be normalized
    const name = 'yves_default';

    // define relative urls to site host (/)
    const urls = {
        // assets base url
        defaultAssets: join('/assets', store.storeKey, store.defaultTheme),
        currentAssets: join('/assets', store.storeKey, currentTheme)
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
            assetPathsCollection.currentAssets = join('./frontend', urls.currentAssets);
        }

        return assetPathsCollection
    };

    // define project relative paths to context
    const paths = {
        // locate the typescript configuration json file
        tsConfig: './tsconfig.json',

        assets: assetPaths(),

        // public folder with all assets
        publicAll: globalSettings.paths.publicAll,

        // current store and theme public assets folder
        public: join('./public/Yves', urls.currentAssets),

        // core folders
        core: {
            // all modules
            modules: './vendor/spryker/spryker-shop/Bundles'
        },

        // eco folders
        eco: {
            // all modules
            modules: './vendor/spryker-eco'
        },

        // project folders
        project: {
            // all modules
            modules: './src/Pyz/Yves'
        }
    };

    // define entry point patterns for current theme, if current theme is defined
    const customThemeEntryPointPatterns = function () {
        if (currentTheme !== store.defaultTheme) {
            return [
                ...entryPointsCollection(`**/Theme/${currentTheme}`),
                ...entryPointsCollection(`**/*${store.storeKey}/Theme/${currentTheme}`),
                ...ignoreFiles
            ];
        }

        return [];
    };

    //
    const shopUiEntryPointsPattern = function () {
        if (currentTheme !== store.defaultTheme) {
            return [
                `./ShopUi/Theme/${currentTheme}`,
                `./ShopUi${store.storeKey}/Theme/${currentTheme}`
            ];
        }
        return []
    };

    // return settings
    return {
        name,
        store,
        paths,
        urls,

        context: globalSettings.context,

        // define settings for suite-frontend-builder finder
        find: {
            // webpack entry points (components) finder settings
            componentEntryPoints: {
                // absolute dirs in which look for
                dirs: [
                    join(globalSettings.context, paths.core.modules),
                    join(globalSettings.context, paths.eco.modules),
                    join(globalSettings.context, paths.project.modules)
                ],
                // files/dirs patterns
                patterns: customThemeEntryPointPatterns(),
                fallbackPatterns: [
                    ...entryPointsCollection(`**/Theme/${store.defaultTheme}`),
                    ...entryPointsCollection(`**/*${store.storeKey}/Theme/${store.defaultTheme}`),
                    ...ignoreFiles
                ]
            },

            // core component styles finder settings
            // important: this part is used in shared scss environment
            // do not change unless necessary
            componentStyles: {
                // absolute dirs in which look for
                dirs: [
                    join(globalSettings.context, paths.core.modules)
                ],
                // files/dirs patterns
                patterns: [
                    `**/Theme/${store.defaultTheme}/components/atoms/*/*.scss`,
                    `**/Theme/${store.defaultTheme}/components/molecules/*/*.scss`,
                    `**/Theme/${store.defaultTheme}/components/organisms/*/*.scss`,
                    `**/Theme/${store.defaultTheme}/templates/*/*.scss`,
                    `**/Theme/${store.defaultTheme}/views/*/*.scss`,
                    `!**/Theme/${store.defaultTheme}/**/style.scss`,
                    ...ignoreFiles
                ]
            },

            shopUiEntryPoints: {
                dirs: [
                    join(globalSettings.context, './src/Pyz/Yves/')
                ],
                patterns: [
                    ...shopUiEntryPointsPattern()
                ],
                fallbackPatterns: [
                    `./ShopUi/Theme/${store.defaultTheme}`,
                    `./ShopUi${store.storeKey}/Theme/${store.defaultTheme}`
                ]
            }
        }
    }
}

module.exports = {
    globalSettings,
    getAppSettingsByStore
};
