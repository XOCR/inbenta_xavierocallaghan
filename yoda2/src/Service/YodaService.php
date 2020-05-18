<?php
/* /src/Service/YodaService.php */

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class YodaService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function getAccessTokenAction()
    {
        $inbentaApiKey =  $this->params->get('inbentaAPIKey');
        $inbentaSecret =  $this->params->get('inbentaSecret');

        $header = [
            'x-inbenta-key' => $inbentaApiKey
        ];

        $body = [
            'secret' => $inbentaSecret
        ];

        $client = HttpClient::create(['headers' => $header]);
        $response = $client->request('POST', 'https://api.inbenta.io/v1/auth', ['body' => $body]);

        return $response;
    }

    public function getAPIChatbotUrl($accessToken){

        $inbentaApiKey =  $this->params->get('inbentaAPIKey');
        $header = [
            'x-inbenta-key' => $inbentaApiKey,
            'Authorization' => 'Bearer '.$accessToken
        ];

        $client = HttpClient::create(['headers' => $header]);
        $response = $client->request('GET', 'https://api.inbenta.io/v1/apis');

        return $response;
    }

    public function newConversationAction($url, $token)
    {
        $url .= '/v1/conversation';
        $inbentaApiKey =  $this->params->get('inbentaAPIKey');

        $header = [
            'x-inbenta-key' => $inbentaApiKey,
            'Authorization' => 'Bearer '.$token,
            'x-inbenta-source' => 'string',
            'x-inbenta-user-type' => 0,
            'x-inbenta-env' => 'production'
        ];

        $payLoadParams =  [
            'answers'=> [
                'sideBubbleAttributes' => 'SIDEBUBBLE_TEXT',
                'answerAttributes' => 'ANSWER_TEXT',
                'answerAttributes' => 'ANSWER_TEXT',
                'maxOptions' => 4,
            ] ,
            'forms'=> [
                'allowUserToAbandonForm' => true,
                'errorRetries' => 3,
            ] ,
            'tracking'=> [
                'lang' => 'en',
                'timezone' => 'Europe/Paris',
            ] ,
        ];

        $body = [
            'payload' => $payLoadParams
        ];

        $client = HttpClient::create(['headers' => $header]);
        $response = $client->request('POST', $url, ['body' => $body]);

        return $response;

    }

    public function askMessageAction($url, $accessToken, $sessToken, $question)
    {
        $url .= '/v1/conversation/message';
        $inbentaApiKey =  $this->params->get('inbentaAPIKey');

        $header = [
            'x-inbenta-key' => $inbentaApiKey,
            'Authorization' => 'Bearer '.$accessToken,
            'x-inbenta-session' => 'Bearer '.$sessToken,
        ];

        $body =  [
            'message' => $question ,
        ];

        $client = HttpClient::create(['headers' => $header]);
        $response = $client->request('POST', $url, ['body' => $body]);
        return $response;

    }


}