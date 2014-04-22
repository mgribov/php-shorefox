<?php

namespace Triptelligent\Storage\Backend;

class Mongo implements BackendInterface {
    
    protected $mongo;
    protected $database;
    protected $collection;
    
    public function __construct(array $config) {
        if (!class_exists('MongoClient')) {
            throw new \Exception("Mongo extension is missing");
        }
        
        $this->mongo = new \MongoClient($config['connection']);
        $this->database = $this->mongo->$config['database'];
        $this->collection = $this->database->$config['collection'];
    } 
    
    public function put($data) {
        $this->collection->ensureIndex(array('path' => 1), array('unique' => true));
        return $this->collection->insert($data);
    }
    
    public function delete($path) {
        return $this->collection->remove(array('path' => $path));
    }
    
    public function get($path) {
        return $this->collection->findOne(array('path' => $path));
    }
    
}