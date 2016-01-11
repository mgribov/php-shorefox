<?php

namespace Shorefox\Endpoint;

class CruiseShips extends Endpoint {

    protected $path = 'cruise_ships';

    /**
     * Get a list of cruise ships for a specific cruise line
     * @see https://api.shorefox.com/doc/public/cruise_ships/getting_a_list_of_cruise_ships_for_a_specific_cruise_line.html
     * 
     * @param integer $cruiseline
     * @return array 
     */
    public function getForCruiseLine($cruiseline) {
        return $this->getAll(array('for_cruise_line' => $cruiseline));
    }

}
