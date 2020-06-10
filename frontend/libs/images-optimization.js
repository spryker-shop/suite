const imagemin = require('imagemin');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');
const imageminGifsicle = require('imagemin-gifsicle');

const { lstatSync, readdirSync } = require('fs');
const { join } = require('path');

const imagesOptimization = (appSettings) => {
    let isPublicOutput = false;
    let isOptimize = true;

    const currentMode = process.argv.slice(2)[0];
    Object.keys(appSettings.imageOptimizationOptions.enableModes).map(mode => {
        if (currentMode === mode) {
            isPublicOutput = true;
            isOptimize = appSettings.imageOptimizationOptions.enableModes[mode];
        }
    });

    if (isOptimize) {
        try {
            Object.values(appSettings.paths.assets).reduce((copyConfig, assetsPath) => {
                const assetPattern = `${assetsPath}/images/**/*.{jpg,png,gif,svg}`;
                let outputPath = `${assetsPath}/images/optimized-images/`;

                if (isPublicOutput) {
                    outputPath = `${appSettings.paths.public}/images/`;
                }

                imagemin([assetPattern], {
                    destination: outputPath,
                    plugins: [
                        imageminMozjpeg(appSettings.imageOptimizationOptions.jpg),
                        imageminPngquant(appSettings.imageOptimizationOptions.png),
                        imageminSvgo({
                            plugins: [appSettings.imageOptimizationOptions.svg],
                        }),
                        imageminGifsicle(appSettings.imageOptimizationOptions.gif),
                    ],
                })
            }, []);

            console.info(`${appSettings.namespaceConfig.namespace} (${appSettings.theme} theme) --> images successfully compressed!`);
        } catch ({message}) {
            console.error('Images compression has been interrupted with error: ', message);
        }
    }
};

module.exports = imagesOptimization;
