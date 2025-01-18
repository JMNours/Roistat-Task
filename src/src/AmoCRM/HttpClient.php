<?php

namespace Project\AmoCRM;

use CurlHandle;
use Exception;

class HttpClient
{
    private CurlHandle $ch;

    private string $url;
    private array $postFields = [];
    private array $getParams = [];
    private string $method = 'GET';
    private array $headers = [];

    public function __construct()
    {
        $this->ch = curl_init();
    }

    public function execute()
    {
        if(!isset($this->url))
            throw new Exception('Url is empty');
        
        if (!empty($this->getParams)) {
            curl_setopt($this->ch, CURLOPT_URL, $this->url. "?" . http_build_query($this->getParams));
        } else {
            curl_setopt($this->ch, CURLOPT_URL, $this->url);
        }

        if(!empty($this->postFields)){
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($this->postFields));
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

        curl_setopt($this->ch, CURLOPT_HEADER  ,true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($this->ch);
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    public function setPostFields(array $params)
    {
        $this->postFields = $params;
    }

    public function setGetParams(array $params)
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
    
    public function getHttpCode()
    {
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }
}
