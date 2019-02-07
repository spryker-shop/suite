// get the webpack compiler
const compiler = require('./libs/compiler');

// get the mode arg from `npm run xxx` script defined in package.json
const [mode] = process.argv.slice(2);

// get the webpack configuration associated with the provided mode
const getConfiguration = require(`./configs/${mode}`);

// build the project
getConfiguration()
    .then(config => compiler.compile(config))
    .catch(error => console.error('An error occur while creating configuration', error));
