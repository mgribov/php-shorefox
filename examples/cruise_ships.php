<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// set $api_token and $api_secret
include 'auth.php';

try {
    // we need to store HTTP response headers and responses to comply with API caching policy
    $storage_config = array(
        'connection' => 'mongodb://127.0.0.1:27017',
        'database' => 'shorefox',    
        'collection' => 'api',
        );
    
    $trip = new \Shorefox\Client\Client($api_token, $api_secret, $storage_config, true);

    // get all cruise lines
    $cruiselines_all = $trip->getCruiseLines()->getAll();

    // get all ships
    $ships_all = $trip->getCruiseShips()->getAll();

    // get all ships for carnival cruise lines
    $ships_carnival = $trip->getCruiseShips()->getForCruiseLine(68);
} catch (\Exception $e) {
    echo "{$e->getMessage()}, {$e->getCode()}\n";
}
