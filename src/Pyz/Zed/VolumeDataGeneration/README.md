# Volume data generator

### Generation demo data for the specific entity type
```bash
docker/sdk testing console volume-data:generate -e Companies
```

### Generation demo data for the all entity types
All entity types are listed in `VolumeDataGenerationConfig::getEntityTypes()`
```bash
docker/sdk testing console volume-data:generate -a
```

Notes:
- Some generator tests use direct entity creation instead of helpers.
It's caused by storing each executed helper action in $test->tester->scenarion->steps.
- Using of transfer builders slows down execution of a command, especially if there is `unique` in a transfer property rule. E.g.: <property name="reference" dataBuilderRule="unique()->regexify('[A-Za-z0-9]{5}')"/>
