module.exports = {
    extends: ['stylelint-config-standard-less', '@spryker/frontend-config.stylelint/.stylelintrc.json'],
    rules: {
        'no-empty-source': null,
        'selector-max-class': 4,
        'selector-max-compound-selectors': 4,
        'selector-max-combinators': 4,
        'selector-class-pattern': null,
        'less/no-duplicate-variables': null,
        'less/color-no-invalid-hex': null,
    },
};
