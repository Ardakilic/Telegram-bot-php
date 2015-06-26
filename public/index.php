<?php

/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-PHP-Example-Bot
 * @version  0.1
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     https://arda.pw
 */

//Show all errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../config.php';

//Guzzle suggested having this, so here goes
date_default_timezone_set($config['timezone']);

require_once __DIR__ . '/../vendor/autoload.php';


//We need this to play with paths
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

//Enable Debugging
$app['debug'] = true;



$app->get('/set_webhook/{bot}', function ($bot, Request $request) use ($app, $config) {

    //Token not found!
    if (!array_key_exists($bot, $config['tokens'])) {
        $app->abort(404);
    }

    //If bot name is not in the predefined routes then we are aborting
    $filpped_routes = array_flip($config['webhook_urls']);
    if (!array_key_exists($bot, $filpped_routes)) {
        $app->abort(404);
    }

    //Let's make a new guzzle connection and set the hook:
    $client = new GuzzleHttp\Client();
    $res = $client->get('https://api.telegram.org/bot' . $config['tokens'][$bot] . '/setWebhook?url=https://' . $request->getHttpHost() . '/hook/' . $filpped_routes[$bot]);
    return $res->getBody();

});


$app->post('/hook/{route}', function ($route, Request $request) use ($app, $config) {

    //If the route is not found, abort the app.
    if (!array_key_exists($route, $config['webhook_urls'])) {
        $app->abort(404);
    }

    //This is our bot, now we can catch accordingly
    $bot = $config['webhook_urls'][$route];

    //Sample response, leaving here for reference.
    //Not sure but if chat id is negative it's a group.
    //{"update_id":44897803,"message":{"message_id":41,"from":{"id":111111,"first_name":"Eser","last_name":"Ozvataf","username":"xxxxxx"},"chat":{"id":-111111,"title":"chatname"},"date":1435239370,"text":"@MyBot testing"}}

    $response = file_get_contents('php://input');

    $responseData = json_decode($response, true);

    //Token not found!
    if (!array_key_exists($bot, $config['tokens'])) {
        $app->abort(404);
    }


    //Set the message, and if responses are not found, fall back to default message
    $message = $config['responses']['fallback_response'];

    if (array_key_exists($bot, $config['responses'])) {
        //prevent the reusage
        $botMessages = $config['responses'][$bot];

        //The text that user has typed, let's delete the bot name
        $userInput = trim(str_replace('@' . $bot, "", $responseData['message']['text']));
        
        //If user only says "/" it also triggers the webhook, we don't want to return anything (API bug?)
        if(stripos($userInput, '/') !== false && stripos($userInput, $bot) === false) {
            return 'OK';
        }

        //Now let's split it by spaces
        $userInputArray = explode(" ", $userInput);

        //We will be checking each words one by one, first matched result will base the message
        foreach ($userInputArray as $input) {
            if (array_key_exists($input, $botMessages)) {
                //If there are messages set, fetch a random element
                $message = $botMessages[$input][array_rand($botMessages[$input], 1)];
                break;
            }
        }
    }


    //now let's reply!
    //https://core.telegram.org/bots/api#sendmessage
    $client = new GuzzleHttp\Client();

    $queryArray = [
        'chat_id' => (string)$responseData['message']['chat']['id'],
        'text' => $message,
    ];
    
    if($config['as_reply']) {
    	$queryArray['reply_to_message_id'] = (string)$responseData['message']['message_id'];
    }

    if(!$config['preview_links']) {
        $queryArray['disable_web_page_preview'] = true;
    }

    $client->get('https://api.telegram.org/bot' . $config['tokens'][$bot] . '/sendMessage?' .
        http_build_query($queryArray)
    );

    //Telegram API wants something in return, else it tries infinitely
    return 'OK';

});

$app->run();
