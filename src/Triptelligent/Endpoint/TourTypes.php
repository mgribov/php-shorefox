<?php

namespace Triptelligent\Endpoint;

class TourTypes extends Endpoint {

    protected $path = 'tour_types';

    /**
     * Get a list of tour types for a specific tour sub-type
     * @see https://api.triptelligent.com/doc/public/tour_types/getting_a_list_of_tour_types_for_a_specific_tour_subtype.html
     * 
     * @param integer $s
     * @return array
     */
    public function getWithTourSubType($s) {
        return $this->getAll(array('with_tour_sub_type' => $s));
    }

}
