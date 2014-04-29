# Triptelligent REST API PHP5 client

Consumes [triptelligent.com](http://triptelligent.com) REST API

**NOTE:** Currently, supports read-only mode, i.e. no booking via POST to /bookings and no support for /config endpoint.

## Install via Composer into existing project
    curl -sS https://getcomposer.org/installer | php # if composer is not installed
    ./composer.phar require mgribov/php-triptelligent

## Local Storage
Clients consuming the API are required to respect ETag and Cache-Control headers returned, and cache data locally.

For now, only 1 storage engine is supported, MongoDB.

See [src/Triptelligent/Storage](https://github.com/mgribov/php-triptelligent/tree/master/src/Triptelligent/Storage) for details

## Examples
See [examples/](https://github.com/mgribov/php-triptelligent/tree/master/examples) folder for all use cases

Make sure you install composer and run "./composer.phar dump-autoload" first to use the examples