<?php

namespace Triptelligent\Storage;

/**
 * Manages a list of saved paths, their responses and cache/etag data 
 */
class HttpStorage implements HttpStorageInterface, \Triptelligent\Debug\DebugInterface {
    
    protected $debug = false;
    
    protected $backend;
    
    /**
     * Object format
     * @var array
     */
    protected $object = array(
        'path' => null,
        'cache' => null,
        'etag' => null,
        'response' => null,
        );
    
    /**
     *
     * @param bool $v 
     */
    public function setDebug($v) {
        $this->debug = $v;
    }
    
    /**
     *
     * @param \Triptelligent\Storage\Backend\BackendInterface $b 
     */
    public function setBackend(\Triptelligent\Storage\Backend\BackendInterface $b) {
        $this->backend = $b;
    }
    
    /**
     *
     * @return integer
     */
    public function getCache() {
        return $this->object['cache'];
    }
    
    /**
     *
     * @return string
     */
    public function getEtag() {
        return $this->object['etag'];
    }
    
    /**
     *
     * @return array
     */
    public function getResponse() {
        return $this->object['response'];
    }
    
    /**
     *
     * @return bool
     */
    public function isCurrent() {
        return $this->object['cache'] > time();
    }

    /**
     *
     * @param string $path
     * @return mixed 
     */
    public function remove($path) {
        return $this->backend->delete($path);
    }
    
    /**
     *
     * @param string $path
     * @return array 
     */
    public function get($path) {
        $this->object = $this->backend->get($path);        
        return $this->object;
    }
    
    /**
     * 
     * @param string $path
     * @param array $response
     * @param string $header
     * @return array 
     */
    public function save($path, $response, $header) {
        $all_headers = array();

        // collect all headers in nicer format
        $headers = explode("\r\n", $header);
        foreach ($headers as $h) {
            $a = explode(':', $h);
            if (count($a) == 2) {
                $all_headers[trim($a[0])] = trim($a[1]);
            }
        }

        $etag = array_key_exists('Etag', $all_headers) ? preg_replace('/"/', '', $all_headers['Etag']) : null;
        
        // get max-age
        $cache = array();
        preg_match('/max-age=(\d+)/', $all_headers['Cache-Control'], $cache);

        // @todo in both cases, we currently delete the object from storage and put a new one
        if ($etag != $this->object['etag']) {
            $this->__debug("new etag for $path, saving locally");
            
            $this->object['path'] = $path;
            $this->object['response'] = $response; 
            $this->object['cache'] = (int)(time() + $cache[1]);
            $this->object['etag'] = $etag;
            $this->backend->delete($path);
            $this->backend->put($this->object);
            
        } elseif ($headers[0] == "HTTP/1.1 304 Not Modified") {
            $this->__debug("got 304 for etag for $path, bumping local cache timer");
            
            $this->object['cache'] = (int)(time() + $cache[1]);
            $this->backend->delete($path);
            $this->backend->put($this->object);
        }
        
        return $this->object;
    }
        
    /**
     *
     * @param mixed $data 
     */    
    public function __debug($data) {
        if ($this->debug) {
            var_dump($data);
        }
    }
}
