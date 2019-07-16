# Deploy file version `1` reference

This reference page describes version `1` of the Deploy file format. 
This is the newest version.

## Glossary

1. ***Deploy file*** - The declaration for Spryker tools to make deployment of Spryker applications 
    in different environments. Represented in YAML format.

1. ***Region*** - Defines the isolated instance(s) of Spryker applications, that
    - has the only one persistent database to work with;
    - limits visibility of project's Stores to operate only with Stores that belongs to the Region;
    - relates to geographical terms like `data centres`/`regions`/`continents` in the real world.

1. ***Group*** - Defines the group of Spryker applications within the Region, that
    - is scaled separately from other groups;
    - can be assumed as `auto scaling group` in the Cloud.

1. ***Store*** - The store-related context in which a request is processed in.

1. ***Application*** - Represent Spryker application, like `Zed`, `Yves`, `Glue`.

1. ***Service*** - External storage or utility service.
    - Represent type, configuration of the service.
    - Configuration can be defined on different levels: project-wide, region-wide, store-specific, endpoint-specific
    with limitations depending on service type.
    
1. ***Endpoint*** - The point of access to ***Application*** or ***Service***.
The key format: "domain[:port]". By default port is 80 for HTTP endpoints.
The port is mandatory for TCP endpoints. 

## Backward compatibility

All version of the Deploy file format will be supported by Spryker tools until a version is marked as deprecated 
in change log and the fact of deprecation is shared via public channels.

## Deploy file structure

The topics here are organized alphabetically for top-level keys along with sub-level keys to describe the hierarchy.

> Note: Extended YAML syntax can be used according to [YAML™ Version 1.2](https://yaml.org/spec/1.2/spec.html)

### Deploy file example

```yaml
version: "1.0"

namespace: spryker_demo
tag: '1.0'

environment: docker
php: 7.2

regions:
    EU:
        services:
            database:
                database: eu-docker
                username: spryker
                password: secret

        stores:
            DE:
                services:
                    broker:
                        namespace: de-docker
                    key_value_store:
                        namespace: 1
                    search:
                        namespace: de_search
            AT:
                services:
                    broker:
                        namespace: at-docker
                    key_value_store:
                        namespace: 2
                    search:
                        namespace: at_search
    US:
        services:
            database:
                database: us-docker
                username: spryker
                password: secret
        stores:
            US:
                services:
                    broker:
                        namespace: us-docker
                    key_value_store:
                        namespace: 3
                    search:
                        namespace: us_search
groups:
    EU:
        region: EU
        applications:
            yves_eu:
                application: yves
                endpoints:
                    yves.de.demo-spryker.com:
                        store: DE
                        services:
                            session:
                                namespace: 1
                    yves.at.demo-spryker.com:
                        store: AT
                        services:
                            session:
                                namespace: 2
            glue_eu:
                application: glue
                endpoints:
                    glue.de.demo-spryker.com:
                        store: DE
                    glue.at.demo-spryker.com:
                        store: AT
            zed_eu:
                application: zed
                endpoints:
                    zed.de.demo-spryker.com:
                        store: DE
                        services:
                            session:
                                namespace: 3
                    zed.at.demo-spryker.com:
                        store: AT
                        services:
                            session:
                                namespace: 4
    US:
        region: US
        applications:
            yves_us:
                application: yves
                endpoints:
                    yves.us.demo-spryker.com:
                        store: US
                        services:
                            session:
                                namespace: 5
            glue_us:
                application: glue
                endpoints:
                    glue.us.demo-spryker.com:
                        store: US
            zed_us:
                application: zed
                endpoints:
                    zed.us.demo-spryker.com:
                        store: US
                        services:
                            session:
                                namespace: 6
services:
    database:
        engine: postgres
        root:
            username: "root"
            password: "secret"
        endpoints:
            localhost:5432:
                protocol: tcp
    broker:
        engine: rabbitmq
        api:
            username: "spryker"
            password: "secret"
        endpoints:
            queue.demo-spryker.com:
    session:
        engine: redis
    key_value_store:
        engine: redis
    search:
        engine: elastic
    scheduler:
        engine: jenkins
        endpoints:
            scheduler.demo-spryker.com:
    mail_catcher:
        engine: mailhog
        endpoints:
            mail.demo-spryker.com:

docker:

    ssl:
        enabled: true

    testing:
        store: DE

    mount:
        baked:
```

### 

#### `version:`

Defines the version of the Deploy file format to read the content according to.

This reference page describes the Deploy file format for versions `1.*`.

```yaml
version: 1.0

namespace: spryker-demo
...
```

#### `namespace:`

Defines the namespace to separate different deployments in the same environment.

As example, Docker images, containers and volumes names starts with the namespace to avoid intersections between 
different deployments on the same host machine.

```yaml
version: 1.0

namespace: spryker-demo
```

#### `tag:`

Defines the tag to separate different boots for the same deployment.

> By default, the tag is unique randomly generated value.

As example, Docker images and volumes are tagged with the tag to avoid intersections between 
different boots for the same deployment on the same host machine. The tag can be set directly in the Deploy file
to make sure all boots of the same deployment run with same images and volumes.

```yaml
version: 1.0

tag: '1.0'
```

```yaml
version: 1.0

tag: 'custom-one'
```

#### `environment:`

Defines the environment name for Spryker applications mainly to point to specific configuration files, 
referring `config/Shared/config-default_%environment_name%*.php`.

`APPLICATION_ENV` environment variable will be set to all corresponding Spryker applications accordingly.

```yaml
version: 1.0

environment: 'docker'
```

#### `image:`

Defines the Docker image to run Spryker applications on. Can be set according to tags for `spryker/php` images placed at  
[Docker Hub](https://hub.docker.com/r/spryker/php/tags). Custom image can be defined here.

```yaml
version: 1.0

image: 'spryker/php:7.2'
```

#### `regions:`

Defines the list of ***Regions***. 

- `regions: services:` defines settings for ***Region***-specific `services:`. Only `database:` is currently allowed here.
- `regions: stores:` defines the list of ***Stores***.
- `regions: stores: services:` defines applications-wide ***Store***-specific settings for ***Services***. 
Only `broker:`, `key_value_store:`, `search:` are currently allowed here. Please, refer to `Services` section for more information.

```yaml
version: "1.0"

regions:
    REGION-1:
        services:
            # Region-specific services settings

        stores:
            STORE-1:
                services:
                    # Store-specific services settings
            STORE-2:
                services:
                    # Store-specific services settings
```

#### `groups:`

Defines the list of ***Groups***.

- `groups: region:` defines link to the ***Region*** by key.
- `groups: applications:` defines the list of ***Applications***. 
For more information see the `groups: applications:` section.

```yaml
version: "1.0"

groups:
    BACKEND-1:
        region: REGION-1
        applications:
            zed_1:
                application: zed
                endpoints:
                    zed.store1.demo-spryker.com:
                        store: STORE-1
                        services:
                            # Application-Store-specific services settings
                    zed.store2.demo-spryker.com:
                        store: STORE-2
                        services:
                            # Application-Store-specific services settings
    STOREFRONT-1:
        region: REGION-1
        applications:
            yves_1:
                application: yves
                endpoints:
                    yves.store1.demo-spryker.com:
                        store: STORE-1
                        services:
                            # Application-Store-specific services settings
                    yves.astore2t.demo-spryker.com:
                        store: STORE-2
                        services:
                            # Application-Store-specific services settings
            glue_1:
                application: glue
                endpoints:
                    glue.store1.demo-spryker.com:
                        store: STORE-1
                    glue.store2.demo-spryker.com:
                        store: STORE-2
```

Applications can be defined as ***Store***-agnostic, as in example above. Also applications can be 
defined as ***Store***-specific, as in example below, by leaving only one endpoint pointing to the application. 
The approaches can be mixed in order to scale applications separately  by ***Store***.

```yaml
version: "1.0"

groups:
    BACKEND-1:
        region: REGION-1
        applications:
            zed_store_1:
                application: zed
                endpoints:
                    zed.store1.demo-spryker.com:
                        store: STORE-1
            zed_store_2:
                application: zed
                endpoints:
                    zed.store2.demo-spryker.com:
                        store: STORE-2
```

#### `groups: applications:`

Defines the list of ***Applications***.

> Note: The key must be project-wide unique.

Each `application:` should contain:
- `groups: applications: application:` defines the type of ***Application***. Possible values are `zed`, `yves`, `glue`.
- `groups: applications: endpoints:` defines the list of ***Endpoints*** to access the ***Application***. 
For more information see the `groups: applications: endpoints:` section.


#### `services:`

Defines a list for ***Services*** and their project-wide settings.

Each services has its own set of settings to be defined. 
Please, refer to `Services` section of this reference page for more details.

The common settings for all services are:

- `engine:` defines the particular 3rd-party application that does the job specific for 
the ***Service*** and is supported by Spryker. E.g. database engine can be currently set to `postgres` or `mysql`.
- `endpoints:` defines a list of ***Endpoints*** that point to the ***Service*** web interface or service's port.

```yaml
services:
    database:
        engine: postgres
        root:
            username: "root"
            password: "secret"

    broker:
        engine: rabbitmq
        api:
            username: "root"
            password: "secret"
        endpoints:
            queue.demo-spryker.com:

    session:
        engine: redis

    key_value_store:
        engine: redis

    search:
        engine: elastic

    scheduler:
        engine: jenkins
        endpoints:
            scheduler.demo-spryker.com:

    mail_catcher:
        engine: mailhog
        endpoints:
            mail.demo-spryker.com:
```

Services settings can be extended on other levels for specific contexts. Please, refer to: 
`regions: services:`,
`regions: stores: services:`,
`groups: applications: endpoints: services:` sections.

#### `groups: applications: endpoints:`

Defines the list of ***Endpoints*** to access the ***Application***.

> Note: The key format is "domain[:port]". The key must be project-wide unique.

- `groups: applications: endpoints: store:` defines the ***Store*** as context to process requests within.
- `groups: applications: endpoints: services:` defines the ***Store***-specific settings for services. 
Only `session:` is currently allowed. Please, refer to `Services` section for more information.

#### `services: endpoints:`

Defines the list of ***Endpoints*** to access the ***Service*** for development or monitoring needs.

> Note: The key format is "domain[:port]". The key must be project-wide unique.

- `services: endpoints: protocol:` defines the protocol. Possible values: `tcp`, `http`. Default is `http`.

> Note: The port must be defined if protocol is set to `tcp`. The TCP port must be project-wide unique.

#### `docker:`

Defines settings for Spryker Docker SDK tools to make deployment based on Docker containers.

```yaml
version: 1.0

docker:

    ssl:
        enabled: true

    testing:
        store: STORE-1

    mount:
        baked:
```

#### `docker: ssl:`

Defines configuration for SSL module in Spryker Docker SDK.

In case when `docker: ssl: enabled:` set to `true` all endpoints work in HTTPS mode. 

```yaml
version: 1.0

docker:
    ssl:
        enabled: true
```
> Note: Register self-sighed CA certificate from `./docker/generator/openssl/default.crt` 
in your system in order to have trusted connections in browser.

#### `docker: debug:`

Defines configuration for debugging.

In case when `docker: debug: enabled:` set to `true` all applications work in debugging mode. 

```yaml
version: 1.0

docker:
    debug:
        enabled: true
```

#### `docker: testing:`

Defines configuration for testing.

- `docker: testing: store:` defines the ***Store*** as context for running tests using specific console commands, 
like `docker/sdk console code:test`.

#### `docker: mount:`

Defines mode for mounting source files into the application containers.

1. `baked:` - source files are copied into image and therefor cannot be changed from host machine.
1. `native:` - source files are mounted from host machine into containers directly. Works perfectly for Linux OS.
1. `docker-sync:` - source files are synced from host machine into containers in runtime. 
It is workaround solution for MacOS and Windows.

As `mount:` is platform-specific setting it is possible to define multiple mount modes. 
Mount mode for particular platform can be specified by using `platforms:` list.
Possible platforms are `windows`, `macos` and `linux`. Please, see the example below.

> Note: The first matching mount mode is taken according to host platform.

```yaml
version: 1.0

docker:
    mount:
        native:
            platforms:
                - linux

        docker-sync:
            platforms:
                - macos
                - windows
```

### Services

#### `database:`

The SQL DBMS ***Service***.

- Project-wide
    - `database: engine:` possible values are `postgres`, `mysql`.
    - `database: root: username`, `database: root: password` define user with `root` privileges.
    - `database: endpoints:` can be defined to expose service's port that can be accessed from outside by given endpoints.
- Region-specific
    - `database: database:` defines a database name
    - `database: username:`, `database: password:` define credentials to access to the database
    
#### `broker:`

The message broker ***Service***.

- Project-wide
    - `broker: engine:` possible values are `rabbitmq`.
    - `broker: api: username`, `database: api: password` define user for Broker's API.
    - `broker: endpoints:` can be defined to expose service's port or/and Web-interface that can be accessed 
    from outside by given endpoints.
- Store-specific
    - `broker: namespace:` defines a namespace (virtual host).
    - `broker: username:`, `broker: password:` define credentials to access to the namespace (virtual host)
    
    
#### `session:`

The key-value store ***Service*** for storing session data.

- Project-wide
    - `session: engine:` possible values are `redis`.
    - `session: endpoints:` can be defined to expose service's port that can be accessed from outside by given endpoints.
- Endpoint-specific
    - `session: namespace:` defines a namespace (number for `redis`).

#### `key_value_store:`

The key-value store ***Service*** for storing business data.

- Project-wide
    - `key_value_store: engine:` possible values are `redis`.
    - `session: endpoints:` can be defined to expose service's port that can be accessed from outside by given endpoints.
- Store-specific
    - `key_value_store: namespace:` defines a namespace (number for `redis`).

#### `search:`

The indexing/search ***Service*** for indexing business data.

- Project-wide
    - `search: engine:` possible values are `elastic`.
    - `session: endpoints:` can be defined to expose service's port that can be accessed from outside by given endpoints.
- Store-specific
    - `search: namespace:` defines a namespace.

#### `scheduler:`

The scheduler ***Service*** to run application-specific jobs periodically in a background.

- Project-wide
    - `scheduler: engine:` possible values are `jenkins`.
    - `scheduler: endpoints:` can be defined to expose service's port or/and Web-interface that can be accessed 
    from outside by given endpoints.

#### `mail_catcher:`

The mail catcher ***Service*** to catch all outgoing emails for development or testing needs.

- Project-wide
    - `mail_catcher: engine:` possible values are `mailhog`.
    - `mail_catcher: endpoints:` can be defined to expose service's port or/and Web-interface that can be accessed 
    from outside by given endpoints.

## Change log

- Introduced initial reference document.
