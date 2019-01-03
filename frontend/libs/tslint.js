const path = require('path');
const { Linter, Configuration } = require("tslint");
const appSettings = require('../settings');

class TSSprykerLinter {
    constructor() {
        this.options = {
            fix: false,
            formatter: this.outputFormatter,
        };

        this.program = Linter.createProgram("tsconfig.json", appSettings.context);
        this.configurationFilename = path.join(appSettings.context, 'tslint.json');
        this.linter = new Linter(this.options, this.program);
        this.files = Linter.getFileNames(this.program);
    }

    lintFiles() {
        this.files.forEach((file) => {
            const fileContents = this.program.getSourceFile(file).getFullText();
            const configuration = Configuration.findConfiguration(this.configurationFilename, file).results;
            this.linter.lint(file, fileContents, configuration);
        });

        this.showLintOutput();
    }

    showLintOutput() {
        const lintingResult = this.linter.getResult();

        console.log(
            lintingResult.output,
            `\x1b[36mErrors count: ${lintingResult.errorCount}\x1b[0m`
        );

        this.exitProcess(lintingResult.errorCount);
    }

    exitProcess(errorCount) {
        if(errorCount > 0 && process.env.NODE_ENV !== 'development') {
            process.exit(1);
        }
    }

    get outputFormatter() {
        const formatterName = process.argv.slice(2)[0];
        return formatterName ? formatterName : 'codeFrame';
    }
}

const tsLint = new TSSprykerLinter();
tsLint.lintFiles();
