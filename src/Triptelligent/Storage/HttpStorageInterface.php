<?php

namespace Triptelligent\Storage;

interface HttpStorageInterface {

    /**
     * Attempt to load a path from storage if it exists 
     */
    public function get($path);
    
    /**
     * Store a URL and its response with its Cache-Control and ETag values
     */
    public function save($path, $response, $header);

    /**
     * Remove a stored URL 
     */
    public function remove($path);
    
    /**
     * Get cache expiration time for the URL 
     */
    public function getCache();
    
    /**
     * Get response value for a stored URL 
     */
    public function getResponse();
    
    /**
     * ETag to be used with If-None-Match request header
     */
    public function getEtag();
    
    /**
     * Is current object still valid 
     */
    public function isCurrent();


}
