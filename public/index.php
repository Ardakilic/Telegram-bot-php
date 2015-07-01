<?php

/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-PHP-Example-Bot
 * @version  0.2
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     https://arda.pw
 */

require __DIR__ . '/../bootstrap.php';

//We need this to play with paths
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

//Enable Debugging
$app['debug'] = true;

$get_bot_config  = $app->share(function($bot) use($config, $app) {
    //Bot not found, or webhook is not set!
    if (!isset($config['bots'][$bot])) {
        $app->abort(404);
    }
    return require __DIR__ . '/../config/bots/' . $config['bots'][$bot] . '.php';
});

$app->get('/deneme/{bot}', function($bot) use($app, $get_bot_config) {
    return $get_bot_config($bot);
});

$app->get('/set_webhook/{bot}', function ($bot, Request $request) use ($app, $config, $get_bot_config) {

    $bot_config = $get_bot_config($bot);

    //Let's make a new guzzle connection and set the hook:
    $client = new GuzzleHttp\Client();

    $res = $client->get('https://api.telegram.org/bot' . $bot_config['token'] . '/setWebhook?url=https://' . $request->getHttpHost() . '/hook/' . $bot);
    
    return $res->getBody();

});


$app->post('/hook/{route}', function ($route, Request $request) use ($app, $config, $get_bot_config) {

    $bot_config = $get_bot_config($route);

    $response = file_get_contents('php://input');

    $responseData = json_decode($response, true);

    if (isset($bot_config['responses'])) {
        //prevent the reusage
        $botMessages = $bot_config['responses'];

        //Set the message, and if responses are not found, fall back to default message
        $message = $botMessages['fallback_response'];

        //The text that user has typed, let's delete the bot name
        $userInput = trim(str_replace('@' . $bot, "", $responseData['message']['text']));
        //If user only says "/" it also triggers the webhook, we don't want to return anything (API bug?)
        if(stripos($userInput, '/') !== false && stripos($userInput, $bot) === false) {
            return 'OK';
        }

        //Now let's split it by spaces
        $userInputArray = explode(" ", $userInput);

        //First, let's check for multiple word occurences
        if(array_key_exists($userInput, $botMessages)) {
            $message = $botMessages[$userInput][array_rand($botMessages[$userInput], 1)];
        } else {
            //If not found, then let's split it by spaces
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
    } else {
        $message = $config['default_fallback_response'];
    }


    //now let's reply!
    //https://core.telegram.org/bots/api#sendmessage
    $client = new GuzzleHttp\Client();

    $queryArray = [
        'chat_id' => (string)$responseData['message']['chat']['id'],
        'text' => $message,
    ];
    
    if($bot_config['as_reply']) {
    	$queryArray['reply_to_message_id'] = (string)$responseData['message']['message_id'];
    }

    if(!$bot_config['preview_links']) {
        $queryArray['disable_web_page_preview'] = true;
    }

    $client->get('https://api.telegram.org/bot' . $bot_config['token'] . '/sendMessage?' .
        http_build_query($queryArray)
    );

    //Telegram API wants something in return, else it tries infinitely
    return 'OK';

});

$app->run();