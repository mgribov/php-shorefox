<?php

namespace Triptelligent\Endpoint;

/**
 * Basic structure for an endpoint 
 */
abstract class Endpoint implements EndpointInterface {
    
    /**
     * Main API cluster
     * 
     * @var string
     */
    protected $prefix = 'https://api.triptelligent.com/';
    
    /**
     * Path after prefix, example 'destinations'
     * 
     * @var string 
     */
    protected $path;
    
    /**
     * 
     * @var Triptelligent\Client\HttpClientInterface 
     */
    protected $http_client;

    /**
     *
     * @param string $id
     * @return \StdClass 
     */
    public function get($id) {
        return $this->http_client->request($this->getPath() . '/' . $id);
    }

    /**
     *
     * @return \StdClass 
     */
    public function getAll() {
        return $this->http_client->request($this->getPath());
    }

    /**
     *
     * @param \Triptelligent\Client\HttpClientInterface $client 
     */
    public function setHttpClient(\Triptelligent\Client\HttpClientInterface $client) {
        $this->http_client = $client;
    }

    /**
     *
     * @return string 
     */
    public function getPath() {
        return $this->prefix . $this->path;
    }

}
