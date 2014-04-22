<?php

namespace Triptelligent\Client;

/**
 * Client to build, send, and receive HTTP and convert JSON response to array
 */
class HttpClient implements HttpClientInterface, \Triptelligent\Debug\DebugInterface {

    protected $token;
    protected $secret;
    protected $storage;
    protected $debug;
    
    /**
     *
     * @param string $token
     * @param string $secret
     */
    public function __construct($token, $secret) {
        $this->token = $token;
        $this->secret = $secret;
    }

    public function setStorage(\Triptelligent\Storage\HttpStorageInterface $storage) {
        $this->storage = $storage;
    }
    
    public function setDebug($v) {
        $this->debug = $v;
    }


    /**
     * Performs curl request against given URL with optional params and optional method
     * 
     * @param string $path - full path before any params 
     * @param array $params - associative array
     * @param string $method - default is GET
     * @return array - exact return from API as an object
     * @throws \Exception 
     */
    public function request($path, $params = array(), $method = 'GET') {
        $etag = null;
        $current = false;
        $headers = array();
        
        $params = http_build_query($params);
        $path = strlen($params) ? $path . '?' . $params : $path;

        // for GETs check local storage first
        if ($this->storage && $method == 'GET') {
            $current = $this->storage->get($path);
            
            if ($current) {
                
                if ($this->storage->isCurrent()) {
                    $this->__debug($path . ': found current copy, serving from cache');
                    
                    return $this->storage->getResponse();
                } 

                $etag = $this->storage->getEtag();

                if ($etag) {
                    $this->__debug($path . ': found current etag will try to use it');
                    $headers[] = 'If-None-Match: "' . $etag . '"';
                }
            }
        }
        
        $res = $this->__curl($path, $method, $headers);
        $response =  json_decode($res['body'], true);
        
        if (is_array($response) && array_key_exists('error', $response)) {
            switch ($response['error']) {
                case "invalid_grant":
                    $msg = 'Invalid API token and/or secret';
                    $code = 403;
                    break;

                default:
                    $msg = "Got error from server: " . $response['error'];
                    $code = 0;
                    if (array_key_exists('status', $response)) {
                        $msg .= " Status: " . $response['status'];
                        $code = $response['status'];
                    }
            } 

            throw new \Exception($msg, $code);

        } elseif (strlen($res['error'])) {
            $msg = "CURL returned error: {$res['error']}";
            throw new \Exception($msg, $res['code']);
        }

        if ($this->storage && $method == 'GET') {
            $this->storage->save($path, $response, $res['header']);
        }
        
        return $response;

    }

    protected function __curl($path, $method = 'GET', $headers = array()) {
        $ret = array();
        
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $headers[] = "Authorization: Basic " . base64_encode($this->token . ':' . $this->secret);
                
        $options = array();
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_HEADER] = true;
        $options[CURLOPT_TIMEOUT] = 5;
        $options[CURLOPT_URL] = $path;
        $options[CURLOPT_CUSTOMREQUEST] = $method;
        $options[CURLOPT_HTTPHEADER] = $headers;

        $curl = curl_init();
        curl_setopt_array($curl, $options); 
        
        $resp = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $ret['header'] = substr($resp, 0, $header_size);
        $ret['body'] = substr($resp, $header_size);        
        $ret['error'] = curl_error($curl);
        $ret['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $ret;        
        
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

