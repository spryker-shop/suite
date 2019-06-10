const { join } = require('path');

// define the current context (root)
const context = process.cwd();

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
            assetPathsCollection.currentAssets = join('./frontend', urls.currentAssets);
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
            ...entryPointsCollection(`**/Theme/${currentTheme}`),
            ...entryPointsCollection(`**/*${store.name}/Theme/${currentTheme}`),
            ...ignoreFiles
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
                    ...entryPointsCollection(`**/Theme/${store.defaultTheme}`),
                    ...entryPointsCollection(`**/*${store.name}/Theme/${store.defaultTheme}`),
                    ...ignoreFiles
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
                    ...ignoreFiles
                ]
            }
        }
    }
}

module.exports = {
    getAppSettingsByStore
};
