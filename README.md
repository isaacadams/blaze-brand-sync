# Blaze Brand Sync

this wordpress plugin syncs brands from blaze into the wordpress `product_brand` taxonomy

### Cron Job

to see that your cron job is scheduled and when it will next run

- install & activate the `WP Crontrol` plugin
- then navigate to `Tools -> Cron Events`
- search for your cron event: `blaze_brand_sync_load`

### Debugging

- debug logs get written to `public_html/wp-content/debug.log`
- open the `public_html/wp-config.php` file in your wordpress environment and search for the following

| name                                   | explanation                                                                                      |
| -------------------------------------- | ------------------------------------------------------------------------------------------------ |
| `define( 'WP_DEBUG', true );`          | enables debugging: leave things just like this to output errors, warnings, notices to the screen |
| `define( 'WP_DEBUG_LOG', true );`      | turn on logging                                                                                  |
| `define( 'WP_DEBUG_DISPLAY', false );` | prevents output of errors, warnings, notices to the screen                                       |
