[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c75bd15a-5d40-4879-9a2f-23e4a6b683e0/mini.png)](https://insight.sensiolabs.com/projects/c75bd15a-5d40-4879-9a2f-23e4a6b683e0)
[![Build Status](https://travis-ci.org/Matth--/toggl-invoiceninja-sync.svg?branch=master)](https://travis-ci.org/Matth--/toggl-invoiceninja-sync)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Matth--/toggl-invoiceninja-sync/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Matth--/toggl-invoiceninja-sync/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Matth--/toggl-invoiceninja-sync/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Matth--/toggl-invoiceninja-sync/?branch=master)

# Invoice syncer
This application is built to sync loggings from toggl to invoiceninja

set the correct parameters in config/parameters.yml

## Installation

- Clone or download the repo
- Use the latest tagged release
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

    # Key = name in toggl (Has to be correct)
    # Value = client id from invoiceninja
    # Use '[]' if you don't need any config here
    clients:
         client_name: 1

    # Key = name in toggl
    # Value= id from invoiceninja
    # Use '[]' if you don't need any config here
    projects:
         first_project: 1
         second_project: 2
```

The key-value pairs in the `clients` variable are important. The key should be the **exact** client name from toggl. The value should be the client id from invoiceninja.
If the time entry was matched with the `clients` variable it skips the part where it checks the `projects` variable. This varialbe acts the same way as the `clients
 variable but instead of matching the client name, it matches the **exact** project name.

## Run the command

to run the command just run:

```bash
php syncer sync:timings
```

## Run as cronjob

As this command syncs the tasks from the current day, this cronjob setting will run the command daily at 23:55.

```bash
55 23 * * * /path/to/php /path/to/syncer sync:timings
```

## Roadmap

- Support for Invoiceninja projects (now it just links project to client as this was written when no projects existed in invoiceninja)
- Next major release will use Symfony 4 components.
