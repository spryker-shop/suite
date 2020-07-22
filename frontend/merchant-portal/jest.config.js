module.exports = {
    name: "merchant-portal",
    roots: ["<rootDir>/../../vendor/spryker"],
    testRegex: ["\/src\/Spryker\/Zed\/.+\/Presentation\/Components\/.+\\.(test|spec)\\.[jt]sx?$"],
    transform: {
        "^.+\\.(ts|js|html)$": "ts-jest",
    },
    resolver: "@nrwl/jest/plugins/resolver",
    moduleFileExtensions: ["ts", "js", "html"],
    collectCoverageFrom: ["**/*.ts", "!**/*.stories.ts", "!**/node_modules/**"],
    coverageReporters: ["lcov", "text"],
    coverageDirectory: "<rootDir>/../../coverage/merchant-portal",
    passWithNoTests: true,
    snapshotSerializers: [
        "jest-preset-angular/build/AngularNoNgAttributesSnapshotSerializer.js",
        "jest-preset-angular/build/AngularSnapshotSerializer.js",
        "jest-preset-angular/build/HTMLCommentSerializer.js",
    ],
};
