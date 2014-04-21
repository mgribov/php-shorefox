<?php

namespace Triptelligent\Endpoint;

class CruiseShips extends Endpoint {

    protected $path = 'cruise_ships';

    /**
     * Get a list of cruise ships for a specific cruise line
     * @see https://api.triptelligent.com/doc/public/cruise_ships/getting_a_list_of_cruise_ships_for_a_specific_cruise_line.html
     * 
     * @param integer $cruiseline
     * @return \StdClass 
     */
    public function getForCruiseLine($cruiseline) {
        return $this->http_client->request($this->getPath(), array('for_cruise_line' => $cruiseline));
    }

}
