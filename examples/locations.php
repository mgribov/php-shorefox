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

    // all countries
    $countries_all = $trip->getCountries()->getAll();

    // all calling codes
    $codes_all = $trip->getCallingCodes()->getAll();

    // USA calling codes
    $codes_usa = $trip->getCallingCodes()->getForCountry('US');

    // get all regions (carbbean, alaska, etc)
    $regions_all = $trip->getRegions()->getAll();

    // get details for caribbean
    $regions_caribbean = $trip->getRegions()->get(3);

    // get all destinations (ports) in caribbean
    $destinations_all = $trip->getDestinations()->getInRegion($regions_caribbean['region']['id']);

    // get info on st maarten
    $destinations_stmaarten = $trip->getDestinations()->get(100);

    // get images for st maarten
    $images_stmaarten = $trip->getDestinations()->getImages($destinations_stmaarten['destination']['id']);

} catch (\Exception $e) {
    echo "{$e->getMessage()}, {$e->getCode()}\n";
}

