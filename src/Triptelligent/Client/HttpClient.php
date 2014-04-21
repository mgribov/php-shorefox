<?php

namespace Triptelligent\Client;

/**
 * Client to build, send, and receive HTTP and convert JSON response to StdClass 
 */
class HttpClient implements HttpClientInterface {

    protected $token;
    protected $secret;
    protected $debug = false;

    /**
     *
     * @param string $token
     * @param string $secret
     * @param bool $debug 
     */
    public function __construct($token, $secret, $debug = false) {
        $this->token = $token;
        $this->secret = $secret;
        $this->debug = $debug;
    }

    /**
     * If set to true, every request will be var_dump()'ed to console
     * 
     * @param bool $v 
     */
    public function setDebug($v) {
        $this->debug = $v;
    }

    /**
     * Performs curl request against given URL with optional params and optional method
     * 
     * @param string $path - full path before any params 
     * @param array $params - associative array
     * @param string $method - default is GET
     * @return \StdClass - exact return from API as an object
     * @throws \Exception 
     */
    public function request($path, $params = array(), $method = 'GET') {
        $params = http_build_query($params);
        $path = strlen($params) ? $path . '?' . $params : $path;

        // @todo must respect Cache-Control and ETag returned by the API

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $headers[] = "Authorization: Basic " . base64_encode($this->token . ':' . $this->secret);

        $options = array();
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_TIMEOUT] = 5;
        $options[CURLOPT_URL] = $path;
        $options[CURLOPT_CUSTOMREQUEST] = $method;
        $options[CURLOPT_HTTPHEADER] = $headers;

        $curl = curl_init();
        curl_setopt_array($curl, $options); 

        $response =  json_decode(curl_exec($curl));
        $error = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($this->debug) {
            var_dump($path, $response);
        }

        if (is_object($response) && property_exists($response, 'error')) {
            switch ($response->error) {
                case "invalid_grant":
                    $msg = 'Invalid API token and/or secret';
                    $code = 403;
                    break;

                default:
                    $msg = "Got error from server: " . $response->error;
                    $code = 0;
                    if (property_exists($response, 'status')) {
                        $msg .= " Status: " . $response->status;
                        $code = $response->status;
                    }
            } 

            throw new \Exception($msg, $code);

        } elseif (strlen($error)) {
            $msg = "CURL returned error: $error";
            throw new \Exception($msg, $code);
        }

        return $response;

    }


}

