<?php

namespace Triptelligent\Client;

/**
 * Simple wrapper to bring individual Endpoint classes and an HTTP client together 
 */
class Client {
    protected $http_client;

    /**
     * Values to be used by HTTP client for the actual request
     * 
     * @param string $token
     * @param string $secret
     * @param bool $debug 
     */
    public function __construct($token, $secret, $debug = false) {
        $this->http_client = new HttpClient($token, $secret, $debug);
    }

    /**
     * Endpoints are defined as classes and are instantiated here
     * example: $client->getDestinations() will give back new instance
     * of \Triptelligent\Endpoint\Destinations()
     * 
     * @param string $endpoint
     * @param array $params
     * @return \Triptelligent\Endpoint\EndpointInterface
     * @throws \Exception 
     */
    public function __call($endpoint, $params = array()) {
        $class = "\\Triptelligent\\Endpoint\\" . preg_replace('/^get/i', '', $endpoint); 
        
        if (class_exists($class)) {
            $c = new $class;
            $c->setHttpClient($this->http_client);
            return $c;
        }

        throw new \Exception("Unknown endpoint $class");
    }

}
