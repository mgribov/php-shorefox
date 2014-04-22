<?php

namespace Triptelligent\Storage;

/**
 * Manages a list of saved paths, their responses and cache/etag data 
 */
class HttpStorage implements HttpStorageInterface, \Triptelligent\Debug\DebugInterface {
    
    protected $debug = false;
    
    protected $backend;
    
    protected $object = array(
        'path' => null,
        'cache' => null,
        'etag' => null,
        'response' => null,
        );
    
    public function setDebug($v) {
        $this->debug = $v;
    }
    
    public function setBackend($b) {
        $this->backend = $b;
    }
    
    public function getCache() {
        return $this->object['cache'];
    }
    
    public function getEtag() {
        return $this->object['etag'];
    }
    
    public function getResponse() {
        return $this->object['response'];
    }
    
    public function isCurrent() {
        return $this->object['cache'] > time();
    }

    public function remove($path) {
        $this->backend->delete($path);
    }
    
    public function get($path) {
        $this->object = $this->backend->get($path);        
        return $this->object;
    }
    
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

        $etag = preg_replace('/"/', '', $all_headers['Etag']);
        
        // get max-age
        $cache = array();
        preg_match('/max-age=(\d+)/', $all_headers['Cache-Control'], $cache);

        if ($etag != $this->object['etag']) {
            $this->__debug("new etag for $path, saving locally");
            
            $this->object['path'] = $path;
            $this->object['response'] = $response; 
            $this->object['cache'] = (int)(time() + $cache[1]);
            $this->object['etag'] = $etag;

            return $this->backend->put($this->object);
        }
    }
        
    public function __debug($data) {
        if ($this->debug) {
            var_dump($data);
        }
    }
}
