<?php

namespace Triptelligent\Endpoint;

class Destinations extends Endpoint {

    protected $path = 'destinations';

    /**
     * Get a list of all destinations in a specific region. 
     * @see https://api.triptelligent.com/doc/public/destinations/getting_a_list_of_destinations_in_a_region.html
     * 
     * @param integer $region
     * @return \StdClass
     */
    public function getForRegion($region) {
        return $this->http_client->request($this->getPath(), array('in_region' => $region));
    }

    /**
     * Get all images for a destination. 
     * @see https://api.triptelligent.com/doc/public/destinations/getting_all_images_for_a_specific_destination.html
     * 
     * @param integer $destination
     * @return \StdClass 
     */
    public function getImages($destination) {
        return $this->http_client->request($this->getPath() . "/$destination/images");
    }

}
