const stylelint = require('stylelint');
const appSettings = require('./settings');

stylelint.lint({
    files: `${appSettings.paths.core.modules}/**/*.scss`,
    syntax: "scss"
}).then(function(data) {
    if (data.errored) {
        process.stdout.write(JSON.stringify(data.output));
    }
}).catch(function(error) {
    console.error(error.stack);
});