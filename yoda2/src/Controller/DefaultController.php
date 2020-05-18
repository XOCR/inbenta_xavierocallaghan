<?php
/* /src/Controller/DefaultController.php */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\YodaService;

class DefaultController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("/", name="index", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        return $this->render('index.html.twig', []);
    }

    /**
     * @param Request $request
     * @Route("/ask", name="ask", options={"expose"=true})
     */
    public function askAction(Request $request, YodaService $yoda)
    {
        $req = $_GET['val'];
        $reqNf = $_GET['nf'];

        if (strpos(strtolower($req), 'force') or (strtolower($req) =='force'))
        {
            return new JsonResponse([
                'data' => 'POKEMON LOCATION LIST (escrita la palabra force)',
                'notfound' => 0
            ]);
        };

        $acessToken = $yoda->getAccessTokenAction()->toArray()['accessToken'];

        // GET the api url with generated AcessToken:
        $url = $yoda->getAPIChatbotUrl($acessToken)->toArray()['apis']['chatbot'];

        // Session for NEW conversation (sessionToken, sessionId)
        $initConversation = $yoda ->newConversationAction($url, $acessToken);
        $sessionToken = $initConversation->toArray()['sessionToken'];

        //MESSAGING
        $messageBack = $yoda->askMessageAction($url, $acessToken, $sessionToken, $req);

        //"no results" control:
        $notfound = 0;
        if (count($messageBack->toArray()['answers'][0]['flags']) > 0)
        {
            ($messageBack->toArray()['answers'][0]['flags'][0] == "no-results") ? $notfound = 1 : $notfound = 0;
        }
        ($notfound == 0) ? $notfound = 0 : $notfound+=$reqNf;

        //if is 3rd "no-results" return pokeapi Characters list
        if ($notfound == 3)
        {
            return new JsonResponse([
                'data' => "AHORA SE LLAMARIA AL POKEAPI PARA MOSTRAR CHARACTERS LIST (3r I am sorry)",
                'notfound' => 0
            ]);
        }else{
            return new JsonResponse([
                'data' => $messageBack->toArray()['answers'][0]['message'],
                'notfound' => $notfound
            ]);
        }

    }

}