const { join } = require('path');
const webpack = require('webpack');
const autoprefixer = require('autoprefixer');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const { findEntryPoints, findStyles } = require('../libs/finder');
const { getAliasFromTsConfig } = require('../libs/alias');

async function getConfiguration(appSettings) {
    const entryPointsPromise = findEntryPoints(appSettings.find.componentEntryPoints);
    const stylesPromise = findStyles(appSettings.find.componentStyles);
    const [entryPoints, styles] = await Promise.all([entryPointsPromise, stylesPromise]);
    const alias = getAliasFromTsConfig(appSettings);

    const copyConfig = function() {
        let config = [];

        for(let asset in appSettings.paths.assets) {
            config = config.concat([
                {
                    from: appSettings.paths.assets[asset],
                    to: '.',
                    ignore: ['*.gitkeep']
                }
            ])
        }

        return config;
    };

    return {
        context: appSettings.context,
        mode: 'development',
        devtool: 'inline-source-map',

        stats: {
            colors: true,
            chunks: false,
            chunkModules: false,
            chunkOrigins: false,
            modules: false,
            entrypoints: false
        },

        entry: {
            'vendor': join(appSettings.context, appSettings.paths.project.shopUiModule, './vendor.ts'),
            'app': [
                join(appSettings.context, appSettings.paths.project.shopUiModule, './app.ts'),
                join(appSettings.context, appSettings.paths.project.shopUiModule, './styles/basic.scss'),
                ...entryPoints,
                join(appSettings.context, appSettings.paths.project.shopUiModule, './styles/util.scss')
            ]
        },

        output: {
            path: join(appSettings.context, appSettings.paths.public),
            publicPath: `${appSettings.urls.assets}/`,
            filename: `./js/[name].js`,
            jsonpFunction: `webpackJsonp_${appSettings.name.replace(/(-|\W)+/gi, '_')}`
        },

        resolve: {
            extensions: ['.ts', '.js', '.json', '.css', '.scss'],
            alias
        },

        module: {
            rules: [
                {
                    test: /\.ts$/,
                    loader: 'ts-loader',
                    options: {
                        context: appSettings.context,
                        configFile: join(appSettings.context, appSettings.paths.tsConfig),
                        compilerOptions: {
                            baseUrl: appSettings.context,
                            outDir: appSettings.paths.public
                        }
                    }
                },
                {
                    test: /\.scss/i,
                    use: [
                        MiniCssExtractPlugin.loader, {
                            loader: 'css-loader',
                            options: {
                                importLoaders: 1
                            }
                        }, {
                            loader: 'postcss-loader',
                            options: {
                                ident: 'postcss',
                                plugins: [
                                    autoprefixer({
                                        'browsers': ['> 1%', 'last 2 versions']
                                    })
                                ]
                            }
                        }, {
                            loader: 'sass-loader'
                        }, {
                            loader: 'sass-resources-loader',
                            options: {
                                resources: [
                                    join(appSettings.context, appSettings.paths.project.shopUiModule, './styles/shared.scss'),
                                    ...styles
                                ]
                            }
                        }
                    ]
                }
            ]
        },

        optimization: {
            runtimeChunk: 'single',
            concatenateModules: false,
            splitChunks: {
                chunks: 'initial',
                minChunks: 1,
                cacheGroups: {
                    default: false,
                    vendors: false
                }
            }
        },

        plugins: [
            new webpack.DefinePlugin({
                __NAME__: `'${appSettings.name}'`,
                __PRODUCTION__: false
            }),

            new CleanWebpackPlugin([
                'js',
                'css',
                'images',
                'fonts'
            ], {
                root: join(appSettings.context, appSettings.paths.public),
                verbose: true,
                beforeEmit: true
            }),

            new CopyWebpackPlugin(copyConfig(), {
                context: appSettings.context
            }),

            new MiniCssExtractPlugin({
                filename: `./css/[name].css`,
            }),

            (compiler) => compiler.hooks.done.tap('webpack', compilationParams => {
                if (process.env.npm_lifecycle_event === 'yves:watch') {
                    return;
                }

                const { errors } = compilationParams.compilation;

                if (!errors || errors.length === 0) {
                    return;
                }

                errors.forEach(error => console.log(error.message));
                process.exit(1);
            })
        ]
    };
}

module.exports = getConfiguration;
