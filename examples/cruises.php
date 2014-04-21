<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// set $api_token and $api_secret
include 'auth.php';

try {
    $trip = new \Triptelligent\Client\Client($api_token, $api_secret, true);

    // get all cruise dates for carnival splendor
    $cruises_carnival_splendor= $trip->getCruiseDates()->getForCruiseShip(5843060);

    // get itinerary for the first carnival splendor cruise returned above
    $cruise = $cruises_carnival_splendor->cruise_dates[0];
    $itin = $trip->getCruiseDates()->getItinerary($cruise->id);
} catch (\Exception $e) {
    echo "{$e->getMessage()}, {$e->getCode()}\n";
}


