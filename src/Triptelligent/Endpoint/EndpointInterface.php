<?php

namespace Shorefox\Endpoint;

interface EndpointInterface {

    /**
     * Get one full first-class object for this endpoint by its id
     * Optionally, pass some filtering params (ex: ?in_region=1234)
     * Optionally, pass a function (ex: /images)
     */
    public function get($id, array $params = array(), $function = null);

    /**
     * Get all objects for this endpoint 
     * Optionally, pass some filtering params (ex: ?in_region=1234)
     */
    public function getAll(array $params = array());

    /**
     * Build base path for this endpoint 
     * example: https://api.shorefox.com/destinations 
     */
    public function getPath();

    /**
     * HTTP client capable for HTTP requests and JSON to array conversion 
     */
    public function setHttpClient(\Scrape\Client\Client $client);

}
