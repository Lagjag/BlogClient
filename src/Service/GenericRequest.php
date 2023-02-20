<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class GenericRequest
{
    public function getRequest($method, $url, $body = null, $bearer = null)
    {
        $httpClient = HttpClient::create();
        $urlApi = 'http://localhost:8080/api/';
        $content = [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'Accept' => 'application/json',
            ],
        ];
        ($bearer) ? $content['auth_bearer'] = $bearer : '';
        ($body) ? $content['body'] = $body : '';

        $response = $httpClient->request(
            $method,
            $urlApi.$url,
            $content
        );

        return $response;
    }
}
