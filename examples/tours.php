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

    // get all tour types
    $tourtypes_all = $trip->getTourTypes()->getAll();
    var_dump($tourtypes_all);

    // get tour types by sub-type, ex for "Water Adventures -> Surf Lessons"
    $tourtypes_all = $trip->getTourTypes()->getWithTourSubType(5);

    // list of tours in caribbean for specific date (date is optional, will otherwise return all caribbean tours)
    $tours_caribbean = $trip->getTours()->getInDestination(3, new \DateTime('2014-05-21'));

    // get tour details for the first tour from above
    $tour = $trip->getTours()->get($tours_caribbean['tours'][0]['id']);

    // get all images for that tour
    $images = $trip->getTours()->getImages($tour['tour']['id']);

    // get guest feedback for the tour
    $feedback = $trip->getTours()->getFeedback($tour['tour']['id']);

    // availability for specific tour from 2014-04-01 to 2014-04-30
    $avail = $trip->getTours()->getAvailability($tour['tour']['id'], new \DateTime('2014-04-01'), new \DateTime('2014-04-30'));

    // check price for tour for 2 adults and 1 child
    $price = $trip->getTours()->getPrice($tour['tour']['id'], 2, 1);

} catch (\Exception $e) {
    echo "{$e->getMessage()}, {$e->getCode()}\n";
}

