<?php

namespace Triptelligent\Debug;

interface DebugInterface {
    
    /**
     * Do something with some data, like log it or dump it 
     */
    public function __debug($data);
    
}