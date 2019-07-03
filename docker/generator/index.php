<?php

use Symfony\Component\Yaml\Parser;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

define('DS', DIRECTORY_SEPARATOR);
define('APPLICATION_SOURCE_DIR', __DIR__ . DS . 'src');
include_once __DIR__ . DS . 'vendor' . DS . 'autoload.php';

$deploymentDir = getenv('SPRYKER_DOCKER_SDK_DEPLOYMENT_DIR') ?: '/tmp';
$projectYaml = getenv('SPRYKER_DOCKER_SDK_PROJECT_YAML') ?: '';
$projectName = getenv('SPRYKER_DOCKER_SDK_PROJECT_NAME') ?: '';
$platform = getenv('SPRYKER_DOCKER_SDK_PLATFORM') ?: 'linux'; // Possible values: linux windows macos

$loader = new FilesystemLoader(APPLICATION_SOURCE_DIR . DS . 'templates');
$twig = new Environment($loader);
$yamlParser = new Parser();

$projectData = $yamlParser->parseFile($projectYaml);

$projectData['_projectName'] = $projectName;
$projectData['tag'] = $projectData['tag'] ?? uniqid();
$projectData['_platform'] = $platform;
$mountMode = $projectData['_mountMode'] = retrieveMountMode($projectData, $platform);
$projectData['_ports'] = retrieveUniquePorts($projectData);
$defaultPort = $projectData['_defaultPort'] = getDefaultPort($projectData);

@mkdir($deploymentDir . DS . 'env' . DS . 'cli', 0777, true);
@mkdir($deploymentDir . DS . 'context' . DS . 'nginx' . DS . 'conf.d', 0777, true);
@mkdir($deploymentDir . DS . 'context' . DS . 'nginx' . DS . 'vhost.d', 0777, true);

file_put_contents(
    $deploymentDir . DS . 'context' . DS . 'nginx' . DS . 'conf.d' . DS . 'front-end.default.conf',
    $twig->render('nginx/conf.d/front-end.default.conf.twig', $projectData)
);
file_put_contents(
    $deploymentDir . DS . 'context' . DS . 'nginx' . DS . 'vhost.d' . DS . 'zed.default.conf',
    $twig->render('nginx/vhost.d/zed.default.conf.twig', $projectData)
);
file_put_contents(
    $deploymentDir . DS . 'context' . DS . 'nginx' . DS . 'vhost.d' . DS . 'yves.default.conf',
    $twig->render('nginx/vhost.d/yves.default.conf.twig', $projectData)
);
file_put_contents(
    $deploymentDir . DS . 'context' . DS . 'nginx' . DS . 'vhost.d' . DS . 'glue.default.conf',
    $twig->render('nginx/vhost.d/glue.default.conf.twig', $projectData)
);
file_put_contents(
    $deploymentDir . DS . 'context' . DS . 'nginx' . DS . 'vhost.d' . DS . 'ssl.default.conf',
    $twig->render('nginx/vhost.d/ssl.default.conf.twig', $projectData)
);
file_put_contents(
    $deploymentDir . DS . 'context' . DS . 'nginx' . DS . 'conf.d' . DS . 'zed-rpc.default.conf',
    $twig->render('nginx/conf.d/zed-rpc.default.conf.twig', $projectData)
);
foreach ($projectData['groups'] ?? [] as $groupName => $groupData) {
    foreach ($groupData['applications'] ?? [] as $applicationName => $applicationData) {
        file_put_contents(
            $deploymentDir . DS . 'env' . DS . $applicationName . '.env',
            $twig->render(sprintf('env/application/%s.env.twig', $applicationData['application']), [
                'applicationName' => $applicationName,
                'applicationData' => $applicationData,
                'project' => $projectData,
                'regionName' => $groupData['region'],
                'regionData' => $projectData['regions'][$groupData['region']],
                'brokerConnections' => getBrokerConnections($projectData),
            ])
        );

        if ($applicationData['application'] === 'zed') {
            foreach ($applicationData['domain'] ?? [] as $domain => $domainData) {
                file_put_contents(
                    $deploymentDir . DS . 'env' . DS . 'cli' . DS . strtolower($domainData['store']) . '.env',
                    $twig->render('env/cli/store.env.twig', [
                        'applicationName' => $applicationName,
                        'applicationData' => $applicationData,
                        'project' => $projectData,
                        'regionName' => $groupData['region'],
                        'regionData' => $projectData['regions'][$groupData['region']],
                        'brokerConnections' => getBrokerConnections($projectData),
                        'storeName' => $domainData['store'],
                        'services' => array_replace_recursive(
                            $projectData['regions'][$groupData['region']]['stores'][$domainData['store']]['services'],
                            $domainData['services'] ?? []
                        ),
                    ])
                );
            }
        }

        if ($applicationData['application'] === 'yves') {
            foreach ($applicationData['domain'] ?? [] as $domain => $domainData) {
                if ($domainData['store'] !== ($projectData['docker']['testing']['store'] ?? '')) {
                    continue;
                }
                file_put_contents(
                    $deploymentDir . DS . 'env' . DS . 'cli' . DS . 'testing.env',
                    $twig->render('env/cli/testing.env.twig', [
                        'applicationName' => $applicationName,
                        'applicationData' => $applicationData,
                        'project' => $projectData,
                        'domain' => strtok($domain, ':'),
                        'port' => strtok($domain) ?: $defaultPort,
                        'regionName' => $groupData['region'],
                        'regionData' => $projectData['regions'][$groupData['region']],
                        'brokerConnections' => getBrokerConnections($projectData),
                        'storeName' => $domainData['store'],
                        'services' => array_replace_recursive(
                            $projectData['regions'][$groupData['region']]['stores'][$domainData['store']]['services'],
                            $domainData['services'] ?? []
                        ),
                    ])
                );
            }
        }
    }
}
file_put_contents(
    $deploymentDir . DS . 'env' . DS . 'testing.env',
    $twig->render('env/testing.env.twig', [
        'project' => $projectData,
    ])
);

file_put_contents($deploymentDir . DS . 'docker-compose.yml',
    $twig->render('docker-compose.yml.twig', $projectData));
file_put_contents($deploymentDir . DS . 'docker-compose.xdebug.yml',
    $twig->render('docker-compose.xdebug.yml.twig', $projectData));
file_put_contents($deploymentDir . DS . 'docker-compose.test.yml',
    $twig->render('docker-compose.test.yml.twig', $projectData));
file_put_contents($deploymentDir . DS . 'deploy',
    $twig->render('deploy.bash.twig', $projectData));

switch ($mountMode) {
    case 'docker-sync':
        file_put_contents($deploymentDir . DS . 'docker-sync.yml',
            $twig->render('docker-sync.yml.twig', $projectData));
        break;
}

$sslDir = $deploymentDir . DS . 'context' . DS . 'nginx' . DS . 'ssl';
@mkdir($sslDir);
echo shell_exec(sprintf(
    'PFX_PASSWORD="%s" DESTINATION=%s ./openssl/generate.sh %s',
    addslashes($projectData['docker']['ssl']['pfx-password'] ?? 'secret'),
    $sslDir,
    implode(' ', retrieveDomainNames($projectData))
));

copy($sslDir . DS . 'ca.pfx', $deploymentDir . DS . 'spryker.pfx');

// -------------------------
/**
 * @param array $projectData
 * @param string $platform
 *
 * @return string
 */
function retrieveMountMode(array $projectData, string $platform): string
{
    $mountMode = 'baked';
    foreach ($projectData['docker']['mount'] ?? [] as $engine => $configuration) {
        if (in_array($platform, $configuration['platforms'] ?? [$platform], true)) {
            $mountMode = $engine;
            break;
        }
        $mountMode = '';
    }

    if ($mountMode === '') {
        throw new Exception(sprintf('Mount mode cannot be determined for `%s` platform', $platform));
    }

    return $mountMode;
}

/**
 * @param array $projectData
 *
 * @return int[]
 */
function retrieveUniquePorts(array $projectData)
{
    $ports = [
        80 => 80
    ];

    foreach (retrieveEndpoints($projectData) as $domain => $domainData) {
        $port = explode(':', $domain)[1];
        $ports[$port] = $port;
    }

    return $ports;
}

/**
 * @param array $projectData
 *
 * @return array[]
 */
function retrieveEndpoints(array $projectData)
{
    $defaultPort = getDefaultPort($projectData);

    $endpoints = [];

    foreach ($projectData['groups'] ?? [] as $groupName => $groupData) {
        foreach ($groupData['applications'] ?? [] as $applicationName => $applicationData) {
            foreach ($applicationData['domain'] ?? [] as $domain => $domainData) {

                if (strpos($domain, ':') === false) {
                    $domain .= ':' . $defaultPort;
                }

                if (array_key_exists($domain, $endpoints)) {
                    throw new Exception(sprintf(
                        '`%s` domain is used for different applications. Please, make sure domains are unique',
                        $domain
                    ));
                }

                $domainData['region'] = $groupData['region'];
                $domainData['application'] = $applicationName;
                $endpoints[$domain] = $domainData;
            }
        }
    }

    foreach ($projectData['services'] as $serviceName => $serviceData) {
        foreach ($serviceData['domain'] ?? [] as $domain => $domainData) {
            if (strpos($domain, ':') === false) {
                $domain .= ':' . $defaultPort;
            }

            if (array_key_exists($domain, $endpoints)) {
                throw new Exception(sprintf(
                    '`%s` domain is used for different applications. Please, make sure domains are unique',
                    $domain
                ));
            }

            $domainData['service'] = $serviceName;
            $endpoints[$domain] = $domainData;
        }
    }

    return $endpoints;
}

/**
 * @param array $projectData
 *
 * @return string[]
 */
function retrieveDomainNames(array $projectData)
{
    $domains = [];

    foreach (retrieveEndpoints($projectData) as $endpoint => $endpointData) {
        $host = strtok($endpoint, ':');
        $domains[$host] = $host;
    }

    return $domains;
}

/**
 * @param array $projectData
 *
 * @return int
 */
function getDefaultPort(array $projectData): int
{
    $sslEnabled = $projectData['docker']['ssl']['enabled'] ?? false;

    return $sslEnabled ? 443 : 80;
}

/**
 * @param array $projectData
 *
 * @return string
 */
function getBrokerConnections(array $projectData): string
{
    $brokerServiceData = $projectData['services']['broker'];

    $connections = [];
    foreach ($projectData['regions'] as $regionName => $regionData) {
        foreach ($regionData['stores'] ?? [] as $storeName => $storeData) {
            $localServiceData = array_replace($brokerServiceData, $storeData['services']['broker']);
            $connections[$storeName] = [
                'RABBITMQ_CONNECTION_NAME' => $storeName . '-connection',
                'RABBITMQ_HOST' => 'broker',
                'RABBITMQ_PORT' => $localServiceData['port'] ?? 5672,
                'RABBITMQ_USERNAME' => $localServiceData['api']['username'],
                'RABBITMQ_PASSWORD' => $localServiceData['api']['password'],
                'RABBITMQ_VIRTUAL_HOST' => $localServiceData['namespace'],
                'RABBITMQ_STORE_NAMES' => [$storeName], // check if connection is shared
            ];
        }
    }

    return json_encode($connections);
}
