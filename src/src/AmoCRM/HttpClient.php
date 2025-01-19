<?php

namespace Project\AmoCRM;

use CurlHandle;
use Exception;

class HttpClient
{
    private CurlHandle $ch;

    private string $url;
    private $postFields;
    private $getParams;
    private string $method = 'GET';
    private array $headers = [];

    private $response;

    public function __construct()
    {
        $this->ch = curl_init();
    }

    public function execute()
    {
        if(!isset($this->url))
            throw new Exception('Url is empty');
        
        if (isset($this->getParams)) {
            if(is_array($this->getParams))
                $getParams = http_build_query($this->getParams);
            else
                $getParams = $this->getParams;

            curl_setopt($this->ch, CURLOPT_URL, $this->url . "?$getParams");
        } else {
            curl_setopt($this->ch, CURLOPT_URL, $this->url);
        }

        if(isset($this->postFields)){
            if(is_array($this->getParams))
                $postFields = http_build_query($this->postFields);
            else
                $postFields = $this->postFields;

            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postFields);
        }

        if ($this->method != 'GET') {
            if ($this->method == 'POST') {
                curl_setopt($this->ch, CURLOPT_POST, true);
            } elseif($this->method == 'PUT'){
                curl_setopt($this->ch, CURLOPT_PUT, true);
            } else {
                curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->method);
            }
        }

        if(!empty($this->headers)){    
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);
        }
        
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        $this->response = curl_exec($this->ch);
        return $this->response;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    public function setPostFields($params)
    {
        $this->postFields = $params;
    }

    public function setGetParams($params)
    {
        $this->getParams = $params;
    }

    public function addHeader(string $header)
    {
        $this->headers[] = $header;
    }

    public function getRequestInfo()
    {
        return curl_getinfo($this->ch);
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function getHttpCode()
    {
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }
}
