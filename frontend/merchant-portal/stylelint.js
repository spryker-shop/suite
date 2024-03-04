const stylelint = require('stylelint');
const { ROOT_DIR, ROOT_SPRYKER_PROJECT_DIR } = require('./mp-paths');
const commandLineParser = require('commander');

commandLineParser
    .option('-f, --fix', 'execute stylelint in the fix mode.')
    .option('-p, --file-path <path>', 'execute stylelint only for this file.')
    .parse(process.argv);

const defaultFilePaths = [`${ROOT_SPRYKER_PROJECT_DIR}/*/Presentation/Components/**/*.less`];
const filePaths = commandLineParser.filePath ? [commandLineParser.filePath] : defaultFilePaths;

stylelint
    .lint({
        configFile: `${ROOT_DIR}/.stylelintrc.mp.js`,
        files: filePaths,
        syntax: 'less',
        formatter: 'string',
        fix: !!commandLineParser.fix,
    })
    .then(function (data) {
        if (data.errored) {
            const messages = JSON.parse(JSON.stringify(data.output));

            process.stdout.write(messages);
            process.exit(1);
        }
    })
    .catch(function (error) {
        console.error(error.stack);
        process.exit(1);
    });
