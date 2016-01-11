<?php

namespace Shorefox\Endpoint;

/**
 * Basic structure for an endpoint 
 */
abstract class Endpoint implements EndpointInterface {
    
    /**
     * Main API cluster
     * 
     * @var string
     */
    protected $prefix = 'https://api.shorefox.com/';
    
    /**
     * Path after prefix, example 'destinations'
     * 
     * @var string 
     */
    protected $path;
    
    /**
     * 
     * @var Shorefox\Client\HttpClientInterface 
     */
    protected $http_client;

    /**
     * There is a dev version of the API which can be used sometimes
     * @param string $v 
     */
    public function setPrefix($v) {
        $this->prefix = $v;
    }
    
    /**
     * @todo path is not flexible right now
     * 
     * @param array $params 
     * @return array
     */
    public function create(array $params = array()) {
        return $this->http_client->post($this->getPath(), $params);
    }
    
    /**
     *
     * @param string $id
     * @return array 
     */
    public function get($id, array $params = array(), $function = null) {
        $path = $this->getPath() . '/' . $id . $function;        
        return $this->http_client->get($path, $params);
    }

    /**
     *
     * @return array 
     */
    public function getAll(array $params = array()) {
        return $this->http_client->get($this->getPath(), $params);
    }

    /**
     *
     * @param \Shorefox\Client\HttpClientInterface $client 
     */
    public function setHttpClient(\Scrape\Client\Client $client) {
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
