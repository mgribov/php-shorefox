<?php

namespace Triptelligent\Client;

interface HttpClientInterface {

    /**
     * Build and send HTTP request, and return response in StdClass format 
     */
    public function request($path, $params, $method);

}
