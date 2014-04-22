<?php

namespace Triptelligent\Client;

interface HttpClientInterface {

    /**
     * Build and send HTTP request, and return response in array format 
     */
    public function request($path, $params, $method);

}
