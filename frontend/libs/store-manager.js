const getNamespaceMap = (pathToConfig) => {
    const namespaceJson = require(pathToConfig);
    const namespaceMap = new Map();

    namespaceJson.namespaces.forEach((item) => {
        namespaceMap.set(item.namespace, item);
    });

    return namespaceMap;
};

const printWrongNamespaceMessage = namespace => console.warn(`Namespace "${namespace}" does not exist.`);

const getFilteredConfigNamespaces = (requestedArguments) => {
    const namespaceMap = getNamespaceMap(requestedArguments.pathToConfig);

    if (requestedArguments.info === true) {
        console.log('Namespaces with available themes:');
        Array.from(namespaceMap.keys())
             .map(namespaceKey => {
                 console.log(`- ${namespaceKey}`);
                 console.log(`  ${namespaceMap.get(namespaceKey).defaultTheme}`);
                 namespaceMap.get(namespaceKey).themes.forEach(theme => console.log(`  ${theme}`));
             });
        console.log('');
        return [];
    }

    if (requestedArguments.namespaces.length === 0) {
        requestedArguments.namespaces = Array.from(namespaceMap.keys());
    }

    requestedArguments.namespaces
        .filter(requestedNamespace => !namespaceMap.has(requestedNamespace))
        .map(printWrongNamespaceMessage);

    return requestedArguments.namespaces
        .filter(requestedNamespace => namespaceMap.has(requestedNamespace))
        .map(requestedNamespace => {
            const namespaceConfig = Object.assign(namespaceMap.get(requestedNamespace));
            namespaceConfig.themes.push(namespaceConfig.defaultTheme);
            if (requestedArguments.themes.length > 0) {
                requestedArguments.themes.map(theme => {
                    if(!namespaceConfig.themes.includes(theme)){
                        console.warn(`Theme "${theme}" does not exist in "${requestedNamespace}" namespace.`)
                    }
                });
                namespaceConfig.themes = namespaceConfig.themes.filter(namespaceTheme => requestedArguments.themes.includes(namespaceTheme));
            }
            return namespaceConfig;
        });
};

module.exports = {
    getFilteredConfigNamespaces
};
