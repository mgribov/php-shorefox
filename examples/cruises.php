<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// set $api_token and $api_secret
include 'auth.php';

try {
    // we need to store HTTP response headers and responses to comply with API caching policy
    $storage_config = array(
        'class' => "\\Triptelligent\\Storage\\Backend\\Mongo",
        'config' => array(
            'connection' => 'mongodb://127.0.0.1:27017',
            'database' => 'triptelligent', 
            'collection' => 'api',
            )
        );
    
    $trip = new \Triptelligent\Client\Client($api_token, $api_secret, $storage_config, true);

    // get all cruise dates for carnival splendor
    $cruises_carnival_splendor = $trip->getCruiseDates()->getForCruiseShip(5843060);
    
    // get itinerary for the first carnival splendor cruise returned above
    $cruise = $cruises_carnival_splendor['cruise_dates'][0];
    $itin = $trip->getCruiseDates()->getItinerary($cruise['id']);
    
} catch (\Exception $e) {
    echo "{$e->getMessage()}, {$e->getCode()}\n";
}


