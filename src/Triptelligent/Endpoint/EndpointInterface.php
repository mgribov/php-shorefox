<?php

namespace Triptelligent\Endpoint;

interface EndpointInterface {

    /**
     * Get one full first-class object for this endpoint by its id
     */
    public function get($id);

    /**
     * Get all objects for this endpoint 
     */
    public function getAll();

    /**
     * Build base path for this endpoint 
     * example: https://api.triptelligent.com/destinations 
     */
    public function getPath();

    /**
     * HTTP client capable for HTTP requests and JSON to StdClass conversion 
     */
    public function setHttpClient(\Triptelligent\Client\HttpClientInterface $client);

}
