<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// set $api_token and $api_secret
include 'auth.php';

try {
    $trip = new \Triptelligent\Client\Client($api_token, $api_secret, true);

    // get all cruise lines
    $cruiselines_all = $trip->getCruiseLines()->getAll();

    // get all ships
    $ships_all = $trip->getCruiseShips()->getAll();

    // get all ships for carnival cruise lines
    $ships_carnival = $trip->getCruiseShips()->getForCruiseLine(68);
} catch (\Exception $e) {
    echo "{$e->getMessage()}, {$e->getCode()}\n";
}
