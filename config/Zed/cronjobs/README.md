## Spryker Cron Jobs
 - Spryker uses by default Jenkins as cronjob scheduler.
 - Job definitions are located in `config/Zed/cronjobs/{%scheduler-id%}.php` file.
 - Jobs are configured via Jenkins API by console commands (see `vendor/bin/console scheduler:setup`).
