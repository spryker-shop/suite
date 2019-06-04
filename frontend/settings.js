const { join } = require('path');

// define the current context (root)
const context = process.cwd();

// define the default theme
const defaultTheme = 'default';

function getAppSettingsByStore(store) {
    // define the applicatin name
    const name = `${store.name}_${store.theme}`;

    // define relative urls to site host (/)
    const urls = {
        // assets base url
        assets: join('/assets', store.name, store.theme)
    };

    // define project relative paths to context
    const paths = {
        // locate the typescript configuration json file
        tsConfig: './tsconfig.json',

        // global assets folder
        globalAssets: './frontend/assets/global',

        // assets folder
        assets: join('./frontend', urls.assets),

        // public folder
        public: join('./public/Yves', urls.assets),

        // core folders
        core: {
            // all modules
            modules: './vendor/spryker/spryker-shop/Bundles',
            // ShopUi source folder
            shopUiModule: `./vendor/spryker/spryker-shop/Bundles/ShopUi/src/SprykerShop/Yves/ShopUi/Theme/${defaultTheme}`
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
            shopUiModule: `./src/Pyz/Yves/ShopUi/Theme/${defaultTheme}`
        }
    };

    // define fallback entry points if current theme ont the same as default
    let fallbackComponentEntryPointPatterns = [];
    if (defaultTheme !== store.theme) {
        fallbackComponentEntryPointPatterns = [
            `**/Theme/${defaultTheme}/components/atoms/*/index.ts`,
            `**/Theme/${defaultTheme}/components/molecules/*/index.ts`,
            `**/Theme/${defaultTheme}/components/organisms/*/index.ts`,
            `**/Theme/${defaultTheme}/templates/*/index.ts`,
            `**/Theme/${defaultTheme}/views/*/index.ts`,
            `**/*${store.name}/Theme/${defaultTheme}/components/atoms/*/index.ts`,
            `**/*${store.name}/Theme/${defaultTheme}/components/molecules/*/index.ts`,
            `**/*${store.name}/Theme/${defaultTheme}/components/organisms/*/index.ts`,
            `**/*${store.name}/Theme/${defaultTheme}/templates/*/index.ts`,
            `**/*${store.name}/Theme/${defaultTheme}/views/*/index.ts`
        ]
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
                patterns: [
                    `**/Theme/${store.theme}/components/atoms/*/index.ts`,
                    `**/Theme/${store.theme}/components/molecules/*/index.ts`,
                    `**/Theme/${store.theme}/components/organisms/*/index.ts`,
                    `**/Theme/${store.theme}/templates/*/index.ts`,
                    `**/Theme/${store.theme}/views/*/index.ts`,
                    `**/*${store.name}/Theme/${store.theme}/components/atoms/*/index.ts`,
                    `**/*${store.name}/Theme/${store.theme}/components/molecules/*/index.ts`,
                    `**/*${store.name}/Theme/${store.theme}/components/organisms/*/index.ts`,
                    `**/*${store.name}/Theme/${store.theme}/templates/*/index.ts`,
                    `**/*${store.name}/Theme/${store.theme}/views/*/index.ts`,
                    '!config',
                    '!data',
                    '!deploy',
                    '!node_modules',
                    '!public',
                    '!test'
                ],
                fallbackPatterns: fallbackComponentEntryPointPatterns
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
                    `**/Theme/${defaultTheme}/components/atoms/*/*.scss`,
                    `**/Theme/${defaultTheme}/components/molecules/*/*.scss`,
                    `**/Theme/${defaultTheme}/components/organisms/*/*.scss`,
                    `**/Theme/${defaultTheme}/templates/*/*.scss`,
                    `**/Theme/${defaultTheme}/views/*/*.scss`,
                    `!**/Theme/${defaultTheme}/**/style.scss`,
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
