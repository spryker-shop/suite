module.exports = {
    name: "merchant-portal",
    roots: ["<rootDir>/../../vendor/spryker"],
    testRegex: ["\/src\/Spryker\/Zed\/.+\/Presentation\/Components\/.+\\.(test|spec)\\.[jt]sx?$"],
    transform: {
        "^.+\\.(ts|js|html)$": "ts-jest",
    },
    roots: ['<rootDir>/../../vendor/spryker/spryker/Bundles'],
    testMatch: ['**/+(*.)+(spec|test).+(ts|js)?(x)'],
    resolver: '@nrwl/jest/plugins/resolver',
    moduleFileExtensions: ['ts', 'js', 'html'],
    collectCoverageFrom: ['**/*.ts', '!**/*.stories.ts', '!**/node_modules/**'],
    coverageReporters: ['lcov', 'text'],
    coverageDirectory: '<rootDir>/../../coverage/merchant-portal',
    passWithNoTests: true,
    transform: { '^.+\\.(ts|js|html)$': 'jest-preset-angular' },
    snapshotSerializers: [
        'jest-preset-angular/build/serializers/no-ng-attributes',
        'jest-preset-angular/build/serializers/ng-snapshot',
        'jest-preset-angular/build/serializers/html-comment',
    ],
};
