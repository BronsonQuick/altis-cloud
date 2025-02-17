# Accessing Logs

You have access to several types of logging from sites on Altis have following logs available:

- PHP Error log
- Nginx Error log
- WordPress Cron task logs (via Cavalcade)
- Email logs
- Access logs (via a custom REST API endpoint)

The logs can be viewed via [Altis Dashboard](./README.md) under a site's Logs tab.

![](../assets/logs.png)

The date range can be specified for all logs types. Some log types will also support additional filtering such as cron logs.

![](../assets/logs-with-filter.png)

## Log Delivery

Log delivery to CloudWatch can be switched off, however this is not recommended as logs will not be available in the Altis Dashboard.

```json
{
    "extra": {
        "altis": {
            "modules": {
                "cloud": {
                    "php-errors-to-cloudwatch": false
                }
            }
        }
    }
}
```
