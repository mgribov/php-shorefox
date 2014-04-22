<?php

namespace Triptelligent\Endpoint;

class CruiseDates extends Endpoint {

    protected $path = 'cruise_dates';

    /**
     * Get a list of cruise dates for a specific cruise ship
     * @see https://api.triptelligent.com/doc/public/cruise_dates/getting_a_list_of_cruise_dates_for_a_specific_cruise_ship.html
     * 
     * @param integer $ship
     * @return array
     */
    public function getForCruiseShip($ship) {
        return $this->getAll(array('for_cruise_ship' => $ship));
    }

    /**
     * Get the itinerary for a specific cruise date
     * @see https://api.triptelligent.com/doc/public/cruise_dates/getting_the_itinerary_for_a_specific_cruise_date.html
     * 
     * @param integer $id
     * @return array 
     */
    public function getItinerary($id) {
        return $this->get($id, array(), '/itinerary');
    }

}
