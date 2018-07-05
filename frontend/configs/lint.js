const merge = require('webpack-merge');
const config = require('./development');
const StyleLintPlugin = require('stylelint-webpack-plugin');

module.exports = merge(config, {
    plugins: [
        new StyleLintPlugin()
    ]
});