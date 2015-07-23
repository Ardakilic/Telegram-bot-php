<?php

/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-Bot-PHP
 * @version  1.0
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     https://arda.pw
 */

require __DIR__ . '/../bootstrap.php';

//We need this to play with paths
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

//Enable Debugging
$app['debug'] = true;

$get_bot_config = $app->share(function ($bot) use ($config, $app) {
    //Bot not found, or webhook is not set!
    if (!isset($config['bots'][$bot])) {
        $app->abort(404);
    }
    return require __DIR__ . '/../config/bots/' . $config['bots'][$bot] . '.php';
});

$app->get('/set_webhook/{bot}', function ($bot, Request $request) use ($app, $config, $get_bot_config) {

    $bot_config = $get_bot_config($bot);

    //Let's make a new guzzle connection and set the hook:
    $client = new GuzzleHttp\Client();

    $res = $client->get('https://api.telegram.org/bot' . $bot_config['token'] . '/setWebhook?url=https://' . $request->getHttpHost() . '/hook/' . $bot);

    return $res->getBody();

});


$app->post('/hook/{route}', function ($route) use ($app, $config, $get_bot_config) {

    $bot_config = $get_bot_config($route);

    $response = file_get_contents('php://input');

    $response_data = json_decode($response, true);

    $helper = new BotHelper($config, $route);
    $response = $helper->fetchMessage($bot_config, $response_data);

    //now let's reply!
    //https://core.telegram.org/bots/api#sendmessage
    $client = new GuzzleHttp\Client();

    if ($config['source'] == 'file') {

        //try {

            $client->get('https://api.telegram.org/bot' . $bot_config['token'] . '/sendMessage?' .
                http_build_query($response['data'])
            );

        /*} catch(Exception $e) {
            //You can catch exceptions and log etc here by uncommenting try/catch statement.
        }*/

    } else {

        //try {

            //http://guzzle.readthedocs.org/en/latest/request-options.html#multipart
            $client->post('https://api.telegram.org/bot' . $bot_config['token'] . '/' . $helper->getEndpoint($response['type']),
                [
                    'multipart' => $response['data'],
                ]
            );

        /*} catch(Exception $e) {

            //You can catch exceptions and log etc here by uncommenting try/catch statement.
 
        }*/

    }

    //Telegram API wants something in return, else it tries infinitely
    return 'OK';

});

$app->run();