[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c75bd15a-5d40-4879-9a2f-23e4a6b683e0/mini.png)](https://insight.sensiolabs.com/projects/c75bd15a-5d40-4879-9a2f-23e4a6b683e0)
[![Build Status](https://travis-ci.org/Matth--/toggl-invoiceninja-sync.svg?branch=master)](https://travis-ci.org/Matth--/toggl-invoiceninja-sync)

# Invoice syncer
This application is built to sync loggings from toggl to invoiceninja

set the correct parameters in config/parameters.yml

## Installation

- Clone the repository
- run `composer install`

Now fill in the parameters
```yaml
parameters:
    debug: false
    serializer.config_dir: %kernel.root_dir%/config/serializer
    seriaizer.cache_dir: %kernel.root_dir%/cache

    toggl.api_key: KEY
    toggl.toggl_base_uri: https://www.toggl.com/api/
    toggl.reports_base_uri: https://www.toggl.com/reports/api/

    invoice_ninja.base_uri: {your-invoice-ninja-url}/api/
    invoice_ninja.api_key: KEY

    # Key = name in toggl
    # Value= id from invoiceninja
    projects:
         first_project: 1
         second_project: 2
```

The key-value pairs in the `projects` variable are important. The key should be the project/task name from toggl. The value should be the client id from invoiceninja.

## Run the command
to run the command just run:

```php
php syncer sync:timings
```
