<?php

namespace Triptelligent\Client;

use \Scrape\Client\Client as HttpClient;

/**
 * Simple wrapper to bring individual Endpoint classes, HTTP client and HTTP storage together 
 */
class Client {
    
    /**
     * HTTP client with local caching support
     * @var \Scrape\Client\Client
     */
    protected $http_client;
    
    /**
     * Change this to https://triptelligent-api-dev.herokuapp.com if you want to use the dev endpoints
     * @var string 
     */
    public $api_url = 'https://api.shorefox.com/';

    /**
     * Values to be used by HTTP client for the actual request
     * 
     * @param string $token
     * @param string $secret
     * @param array $storage_config
     * @param bool debug
     */
    public function __construct($token, $secret, array $storage_config = array(), $debug = false) {
        $config = array(
            'storage' => array(
                'class' => "\\Scrape\\Storage\\Backend\\Mongo",
                'config' => $storage_config,
                ),
            'auth' => array(
                'class' => '\\Scrape\\Auth\\HttpBasic',
                'config' => array(
                    'user' => $token,
                    'secret' => $secret
                    ),
                ),
            );
        
        $this->http_client = new HttpClient($config, $debug);
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
            $c->setPrefix($this->api_url);
            
            return $c;
        }

        throw new \Exception("Unknown endpoint $class");
    }

}
