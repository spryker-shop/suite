export default {
    displayName: 'merchant-portal',
    preset: './jest.preset.js',
    setupFilesAfterEnv: ['<rootDir>/test-setup.ts'],
    roots: ['<rootDir>/../../src/Pyz/Zed'],
    testMatch: ['**/+(*.)+(spec|test).+(ts|js)?(x)'],
    resolver: '@nx/jest/plugins/resolver',
    moduleFileExtensions: ['ts', 'js', 'html'],
    passWithNoTests: true,
    globals: {
        'ts-jest': {
            tsconfig: '<rootDir>/tsconfig.spec.json',
            stringifyContentPathRegex: '\\.(html|svg)$',
        },
    },
};
