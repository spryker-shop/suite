const { join } = require('path');

// define global settings
const globalSettings = {
    // define the current context (root)
    context: process.cwd(),

    // build modes
    modes: {
        dev: 'development',
        watch: 'development-watch',
        prod: 'production'
    },

    paths: {
        // locate the typescript configuration json file
        tsConfig: './tsconfig.json',

        // locate the typescript configuration json file
        namespaceConfig: './config/Yves/frontend-build-config.json',

        // core folders
        core: './vendor/spryker/spryker-shop/Bundles',

        // eco folders
        eco: './vendor/spryker-eco',

        // project folders
        project: './src/Pyz/Yves',

        // assets folders
        publicAssets: './public/Yves/assets'
    }
};

const getAppSettingsByTheme = (namespaceConfig, theme) => {
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

    // getting collection of entry points by pattern
    const entryPointsCollection = (pathPattern) => entryPointsParts.map((element) => `${pathPattern}/${element}`);

    // define the applicatin name
    // important: the name must be normalized
    const name = 'yves_default';

    // define relative urls to site host (/) and pattern to assets
    const urls = {
        // assets base url
        assets: join('/assets', namespaceConfig.namespace, theme)
    };

    // define project relative paths to context
    const paths = {
        // locate the typescript configuration json file
        tsConfig: globalSettings.paths.tsConfig,

        // getting assets paths collection
        assets: {
            // global assets folder
            globalAssets: `./frontend/assets/global/${theme}`,

            // assets folder for current theme into namespace
            currentAssets: join('./frontend', urls.assets)
        },

        // public folder with all assets
        publicAll: globalSettings.paths.publicAll,

        // current namespace and theme public assets folder
        public: join('./public/Yves', urls.assets),

        // core folders
        core: globalSettings.paths.core,

        // eco folders
        eco: globalSettings.paths.eco,

        // project folders
        project: globalSettings.paths.project
    };

    // define if current theme is empty
    const isDefaultTheme = theme === namespaceConfig.defaultTheme;
    const getThemeName = isFallbackPattern => isFallbackPattern ? namespaceConfig.defaultTheme : theme;
    const isFallbackPatternAndDefaultTheme = isFallbackPattern => (isFallbackPattern && isDefaultTheme);

    // define entry point patterns for current theme, if current theme is defined
    const customThemeEntryPointPatterns = (isFallbackPattern = false) => {
        return isFallbackPatternAndDefaultTheme(isFallbackPattern) ? [] : [
            ...entryPointsCollection(`**/Theme/${getThemeName(isFallbackPattern)}`),
            ...entryPointsCollection(`**/*${namespaceConfig.moduleSuffix}/Theme/${getThemeName(isFallbackPattern)}`),
            ...ignoreFiles
        ]
    };

    const shopUiEntryPointsPattern = (isFallbackPattern = false) => (
        isFallbackPatternAndDefaultTheme(isFallbackPattern) ? [] : [
            `./ShopUi/Theme/${getThemeName(isFallbackPattern)}`,
            `./ShopUi${namespaceConfig.moduleSuffix}/Theme/${getThemeName(isFallbackPattern)}`
        ]
    );

    // return settings
    return {
        name,
        namespaceConfig,
        paths,
        urls,

        context: globalSettings.context,

        // define settings for suite-frontend-builder finder
        find: {
            // webpack entry points (components) finder settings
            componentEntryPoints: {
                // absolute dirs in which look for
                dirs: [
                    join(globalSettings.context, paths.core),
                    join(globalSettings.context, paths.eco),
                    join(globalSettings.context, paths.project)
                ],
                // files/dirs patterns
                patterns: customThemeEntryPointPatterns(),
                fallbackPatterns: customThemeEntryPointPatterns(true)
            },

            // core component styles finder settings
            // important: this part is used in shared scss environment
            // do not change unless necessary
            componentStyles: {
                // absolute dirs in which look for
                dirs: [
                    join(globalSettings.context, paths.core)
                ],
                // files/dirs patterns
                patterns: [
                    `**/Theme/${namespaceConfig.defaultTheme}/components/atoms/*/*.scss`,
                    `**/Theme/${namespaceConfig.defaultTheme}/components/molecules/*/*.scss`,
                    `**/Theme/${namespaceConfig.defaultTheme}/components/organisms/*/*.scss`,
                    `**/Theme/${namespaceConfig.defaultTheme}/templates/*/*.scss`,
                    `**/Theme/${namespaceConfig.defaultTheme}/views/*/*.scss`,
                    `!**/Theme/${namespaceConfig.defaultTheme}/**/style.scss`,
                    ...ignoreFiles
                ]
            },

            shopUiEntryPoints: {
                dirs: [
                    join(globalSettings.context, paths.project)
                ],
                patterns: [
                    ...shopUiEntryPointsPattern()
                ],
                fallbackPatterns: [
                    ...shopUiEntryPointsPattern(true)
                ]
            }
        }
    }
};

const getAppSettings = namespaceConfigList => {
    let appSetting = [];
    namespaceConfigList.forEach(namespaceConfig => {
        namespaceConfig.themes.forEach(theme => {
            appSetting.push(getAppSettingsByTheme(namespaceConfig, theme));
        })
    });
    return appSetting;
};

module.exports = {
    globalSettings,
    getAppSettings
};
