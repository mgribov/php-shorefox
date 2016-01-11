<?php

namespace Shorefox\Endpoint;

class Tours extends Endpoint {

    protected $path = 'tours';

    /**
     * Get a list of tours for a destination. 
     * @see https://api.shorefox.com/doc/public/tours/getting_a_list_of_tours_in_a_specific_destination.html
     * @see https://api.shorefox.com/doc/public/tours/getting_a_list_of_tours_that_are_available_on_a_specific_date.html
     * 
     * @param integer $d
     * @param \DateTime $date - optional
     * @return array
     */
    public function getInDestination($d, \DateTime $date = null) {
        $params = array('in_destination' => $d);

        if ($date) {
            $params['on_date'] = $date->format('Y-m-d');
        }

        return $this->getAll($params);
    }

    /**
     * Get the availability per day for a specific tour and timespan. 
     * @see https://api.shorefox.com/doc/public/tours/getting_the_availability_for_a_specific_tour_dayticket_and_timespan.html
     * @see https://api.shorefox.com/doc/public/tours/getting_the_availability_for_a_specific_tour_and_timespan.html
     *  
     * @param integer $id
     * @param \DateTime $from - optional
     * @param \DateTime $to - optional
     * @return array
     */
    public function getAvailability($id, \DateTime $from = null, \DateTime $to = null) {
        $params = array();

        if ($from && $to) {
            $params['from'] = $from->format('Y-m-d');
            $params['to'] = $to->format('Y-m-d');
        }
        
        return $this->get($id, $params, '/availability');
    }

    /**
     * Calculates the total price for booking a specific tour with the given number of people.
     * @see https://api.shorefox.com/doc/public/tours/getting_the_price_for_a_specific_tour_and_number_of_adults__children.html
     * 
     * @param integer $id
     * @param integer $adults
     * @param integer $children
     * @return array 
     */
    public function getPrice($id, $adults = 2, $children = 0) {
        $params = array();
        $params['adult_count'] = $adults;
        $params['child_count'] = $children;

        return $this->get($id, $params, '/price');
    }

    /**
     * Get all images for a tour. 
     * @see https://api.shorefox.com/doc/public/tours/getting_all_images_for_a_specific_tour.html
     * 
     * @param integer $id
     * @return array 
     */
    public function getImages($id) {
        return $this->get($id, array(), '/images');
    }

    /**
     * Get feedback from guests who already took the tour. 
     * @see https://api.shorefox.com/doc/public/tours/getting_guest_feedback_for_a_specific_tour.html
     * 
     * @param integer $id
     * @return array 
     */
    public function getFeedback($id) {
        return $this->get($id, array(), '/feedback');
    }

}
