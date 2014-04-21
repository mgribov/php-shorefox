<?php

namespace Triptelligent\Endpoint;

class TourTypes extends Endpoint {

    protected $path = 'tour_types';

    /**
     * Get a list of tour types for a specific tour sub-type
     * @see https://api.triptelligent.com/doc/public/tour_types/getting_a_list_of_tour_types_for_a_specific_tour_subtype.html
     * 
     * @param integer $s
     * @return \StdClass
     */
    public function getForSubtype($s) {
        return $this->http_client->request($this->getPath(), array('with_tour_sub_type' => $s));
    }

}
