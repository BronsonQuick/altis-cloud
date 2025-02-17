# Healthchecks

Altis uses healthchecks to determine whether servers are healthy or need replacing, and whether minimum requirements such as PHP being available are met before applications are deployed.

These _healthchecks are required for the Cloud environments to operate_ however they can still be toggled on or off. You should never need to turn healthchecks off with the possible exception of local environments.

```json
{
    "extra": {
        "altis": {
            "modules": {
                "cloud": {
                    "healthcheck": false
                }
            }
        }
    }
}
```

## API

There are 2 healthcheck endpoints. If any healthcheck fails the response code will be `500`, otherwise it will be `200`.

**`/__instance_healthcheck`**

Used during deployments to ensure containers meet the minimum requirements for running an application. By default this checks that PHP is available.

**`/__healthcheck`**

Used for application level healthchecks. By default these are:

- PHP is running
- Database is available
- Object cache is available
- Elasticsearch is available
- Sites are indexed in Elasticsearch
- Cavalcade is available
- Cron jobs are running

### Response Format

By default each healthcheck URL will show some HTML output detailing the checks and their status. To get the data in JSON format use one of the following options:

- Send an `Accept` header in the request with the value `application/json`
- Append the query string `?_accept=json`

### CLI Command

A CLI command is also available for the application healthcheck:

```
wp healthcheck run [--format=json]
```

## Extending Healthchecks

Custom healthchecks can be added to the default list using filters. The healthchecks are a keyed array of checks with the value being the result. Any non `true` value counts as a failed healthcheck. Typically an error message should be provided as the alternative value to `true`, however `false` will also work.

**`altis_instance_healthchecks : array`**

Filters the instance healthchecks. These run very early before WordPress has loaded so only core PHP functions and autoloaded code installed via Composer is available.

**`altis_healthchecks : array`**

Filters the application level healthchecks. These are run after WordPress and all plugins have loaded. For example:

```php
add_filter( 'altis_healthchecks', function ( $checks ) {
    global $wpdb;
    $checks['custom-db-table-exists'] = in_array( $wpdb->base_prefix . 'custom', $wpdb->tables, true );
    return $checks;
} );
```
