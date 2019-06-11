const webpack = require('webpack');

// execute webpack compiler
// and nicely handle the console output
function compile(config) {
    console.log(`Building for ${config.mode}...`);

    if (config.watch) {
        console.log('Watch mode: ON');
    }

    webpack(config, (err, stats) => {
        if (err) {
            console.error(err.stack || err);

            if (err.details) {
                console.error(err.details);
            }

            return;
        }

        console.log(stats.toString(config.stats), '\n');
    });
}

// execute webpack compiler on array of configurations
// and nicely handle the console output
function multiCompile(configs) {
    if (configs.length === 0 && configs.length === undefined) {
        return console.error('No configuration provided. Build aborted.');
    }

    if (configs.length === 1) {
        return compile(configs[0]);
    }

    configs.forEach((config, index) => {
        console.log(`${index}. Building for ${config.mode}...`);

        if (config.watch) {
            console.log(`${index}. Watch mode: ON`);
        }
    });

    webpack(configs, (err, multiStats) => {
        if (err) {
            console.error(err.stack || err);

            if (err.details) {
                console.error(err.details);
            }

            return;
        }

        multiStats.stats.forEach(
            (stat, index) => console.log(stat.toString(configs[index].stats), '\n')
        );
    });
}

module.exports = {
    compile,
    multiCompile
}
