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

    // payment key or any other config variables
    $config_all = $trip->getConfig()->getAll();

    // booking config without guests
    // @see https://api.triptelligent.com/doc/public/bookings/successful_tour_booking.html
    $stripe_token = 'tok_3hrjWpCOlBD27Z';
    $booking_config = array(
        'booking' => array(
            'adult_count' => 1,
            'child_count' => 0,
            'time' => '11:00am',
            'date' => '2014-03-31',
            'guest_name' => 'Justus Corkery',
            'guest_email' => 'justus.corkery@homenick.ca',
            'guest_country' => 'US',
            'guest_phone_country_code' => '+1',
            'guest_phone_number' => '437-901-8950',
            'payment_token' => $stripe_token,
            'newsletter_subscribe' => true,
            'tour_id' => 1,
            /*
            'guests_attributes' => array(
                array(
                    'title' => 'Mrs.',
                    'name' => 'Elvie Fadel',
                    'weight' => 83,
                    'weight_unit' => 'kg'
                ),
                array(
                    'title' => 'Mr.',
                    'name' => 'Bob Fadel',
                    'weight' => 83,
                    'weight_unit' => 'kg'
                )
            )
            */
        )
    );
    
    $booking = $trip->getBooking()->create($booking_config);

    var_dump($booking);

} catch (\Exception $e) {
    echo "{$e->getMessage()}, {$e->getCode()}\n";
}

