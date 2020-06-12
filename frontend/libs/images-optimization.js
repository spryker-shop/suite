const imagemin = require('imagemin');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');
const imageminGifsicle = require('imagemin-gifsicle');

const { lstatSync, readdirSync, existsSync } = require('fs');
const { join, normalize } = require('path');

let isGlobalImagesOptimized = false;

const imagesOptimization = appSettings => {
    let isPublicOutput = false;
    let isOptimize = true;

    const currentMode = process.argv.slice(2)[0];

    if (Object.keys(appSettings.imageOptimizationOptions.enableModes).includes(currentMode)) {
        isPublicOutput = true;
        isOptimize = appSettings.imageOptimizationOptions.enableModes[currentMode];
    }

    if (!isOptimize) {
        return;
    }

    try {
        Object.values(appSettings.paths.assets).map(assetsPath => {
            const assetsImagePath = normalize(join(assetsPath, '/images/'));
            const assetsImagePattern = '/*.{jpg,png,svg,gif}';
            let outputPath = normalize(join(assetsPath, '/images/optimized-images/'));

            if (isPublicOutput) {
                outputPath = normalize(join(appSettings.paths.public, '/images/'));
            }

            const isGlobalImages = assetsPath === appSettings.paths.assets.globalAssets;

            if (!existsSync(assetsImagePath) || isGlobalImages && isGlobalImagesOptimized && !isPublicOutput) {
                return;
            }

            const isDirectory = source => lstatSync(source).isDirectory();

            const getDirectories = source =>
                readdirSync(source)
                    .map(name => join(source, name))
                    .filter(isDirectory);

            const getDirectoriesRecursive = source => [
                source,
                ...getDirectories(source)
                    .map(getDirectoriesRecursive)
                    .reduce((a, b) => a.concat(b), [])
            ];

            const assetsImageFolders = getDirectoriesRecursive(assetsImagePath)
                .map(imagePath => normalize(imagePath));

            const outputImageFolders = assetsImageFolders
                .map(imagePath => imagePath.replace(assetsImagePath,''))
                .map(imageInnerFolder => join(outputPath, imageInnerFolder));

            assetsImageFolders.forEach((dir, index) => {
                imagemin([`${dir}${assetsImagePattern}`], {
                    destination: outputImageFolders[index],
                    plugins: [
                        imageminMozjpeg(appSettings.imageOptimizationOptions.jpg),
                        imageminPngquant(appSettings.imageOptimizationOptions.png),
                        imageminSvgo({
                            plugins: [appSettings.imageOptimizationOptions.svg],
                        }),
                        imageminGifsicle(appSettings.imageOptimizationOptions.gif),
                    ]
                });
            });

            if (isGlobalImages) {
                isGlobalImagesOptimized = true;
            }
        }, []);

        console.info(`${appSettings.namespaceConfig.namespace} (${appSettings.theme} theme) --> images successfully compressed!`);
    } catch ({message}) {
        console.error('Images compression has been interrupted with error: ', message);
    }
};

module.exports = imagesOptimization;
