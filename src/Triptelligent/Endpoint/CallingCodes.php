<?php

namespace Triptelligent\Endpoint;

class CallingCodes extends Endpoint {

    protected $path = 'calling_codes';

    /**
     * Get the country code(s) for a specific country. 
     * @see https://api.triptelligent.com/doc/public/calling_codes/getting_the_calling_codes_for_a_specific_country.html
     * 
     * @param string $c
     * @return array
     */
    public function getForCountry($c) {
        return $this->getAll(array('for_country', $c));
    }

}
