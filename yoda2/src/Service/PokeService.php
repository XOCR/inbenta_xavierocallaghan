<?php
/* /src/Service/PokeService.php */

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PokeService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function getCharacterList()
    {
        $pokeApiKey =  $this->params->get('pokeAPIKey');
        $pokeSecret =  $this->params->get('pokeSecret');

        $header = [
        ];

        $body = [
        ];

        $client = HttpClient::create(['headers' => $header]);
        $response = $client->request(
            'POST',
            'https://pokeapi.co/api/v2',
            ['body' => $body]);

        return $response;
    }

    public function getLocationList()
    {
        $pokeApiKey =  $this->params->get('pokeAPIKey');
        $pokeSecret =  $this->params->get('pokeSecret');

        $header = [
        ];

        $body = [
        ];

        $client = HttpClient::create(['headers' => $header]);
        $response = $client->request(
            'POST',
            'https://pokeapi.co/api/v2',
            ['body' => $body]);

        return $response;
    }

}
