<?php

namespace Triptelligent\Client;

/**
 * Simple wrapper to bring individual Endpoint classes, HTTP client and HTTP storage together 
 */
class Client {
    protected $http_client;

    /**
     * Values to be used by HTTP client for the actual request
     * 
     * @param string $token
     * @param string $secret
     * @param array $storage_config
     * @param bool debug
     */
    public function __construct($token, $secret, array $storage_config = array(), $debug = false) {
        $this->http_client = new HttpClient($token, $secret);
        $this->http_client->setDebug($debug);
        
        if (count($storage_config) && array_key_exists('class', $storage_config) && array_key_exists('config', $storage_config)) {
            if (class_exists($storage_config['class'])) {
                $storage_backend = new $storage_config['class']($storage_config['config']);

                $storage = new \Triptelligent\Storage\HttpStorage;
                $storage->setBackend($storage_backend);
                $storage->setDebug($debug);
                
                $this->http_client->setStorage($storage);
            }
        }
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
