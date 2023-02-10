export default {
    displayName: 'merchant-portal',
    preset: './jest.preset.js',
    setupFilesAfterEnv: ['<rootDir>/test-setup.ts'],
    globals: {
        'ts-jest': {
            stringifyContentPathRegex: '\\.(html|svg)$',
            tsconfig: '<rootDir>/tsconfig.spec.json',
        },
    },
    roots: ['<rootDir>/../../vendor/spryker'],
    testMatch: ['**/+(*.)+(spec|test).+(ts|js)?(x)'],
    resolver: '@nrwl/jest/plugins/resolver',
    moduleFileExtensions: ['ts', 'js', 'html'],
    collectCoverageFrom: ['**/*.ts', '!**/*.stories.ts', '!**/node_modules/**'],
    coverageReporters: ['lcov', 'text'],
    coverageDirectory: '<rootDir>/../../coverage/merchant-portal',
    passWithNoTests: true,
};
