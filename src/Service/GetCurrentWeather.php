<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

class GetCurrentWeather extends AbstractController
{
    public function getWeather()
    {
        $httpClient = HttpClient::create();

        $url = "http://api.weatherapi.com/v1/current.json?key=3d333d23e6af41bbab4175626232407&q=Paris&aqi=no";

        try {
            $response = $httpClient->request('GET', $url);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                $weatherData = $response->toArray();

                $temperature = $weatherData['current']['temp_c'];

                return $temperature;
            }
        } catch (\Exception $e) {
            echo 'not found';
        }
    }
}
