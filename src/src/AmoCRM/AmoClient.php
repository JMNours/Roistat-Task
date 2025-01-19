<?php

namespace Project\AmoCRM;

use Exception;

class AmoClient {
    private string $domain;
    private string $accessToken;
    
    public function __construct(string $domain, string $accessToken){
        $this->domain = $domain;
        $this->accessToken = $accessToken;
    }

    public function addLead(array $body){
        $client = $this->getHttpClient();
        $client->setUrl("{$this->domain}/api/v4/leads");
        $client->setMethod('POST');
        $client->setPostFields($body);

        return $this->request($client);
    }

    public function addContact(array $body){
        $client = $this->getHttpClient();
        $client->setUrl("{$this->domain}/api/v4/contacts");
        $client->setMethod('POST');
        $client->setPostFields($body);

        return $this->request($client);
    }

    public function addLeadsComplex(array  $body){
        $client = $this->getHttpClient();
        $client->setUrl("{$this->domain}/api/v4/leads/complex");
        $client->setMethod('POST');
        $client->setPostFields($body);

        return $this->request($client);
    }

    protected function getHttpClient():HttpClient
    {
        $client = new HttpClient();
        $client->addHeader("Authorization: Bearer {$this->accessToken}");
        $client->addHeader("Content-Type:application/json");

        return $client;
    }

    protected function request(HttpClient $client){
        $response = $client->execute();

        if($client->getHttpCode() < 200 && $client->getHttpCode() >= 300){
            throw new Exception('Failed request', $client->getHttpCode());                
        }

        return $response;
    }
}