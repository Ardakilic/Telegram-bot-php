<?php

/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-Bot-PHP
 * @version  1.0
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     https://arda.pw
 */


Class BotHelper
{

    private $config;
    private $route;

    /**
     * @param $config
     * @param $route
     */
    public function __construct($config, $route)
    {
        $this->config = $config;
        $this->route = $route;
    }

    /**
     * This method fetches all the data and the extra info of the response as an array
     * @param $bot_config array Bot configuration, this is needed if the driver is set as file
     * @param $response_data array this is the data that Telegram sends
     * @return array Array of data that will be sent to Telegram
     */
    public function fetchMessage($bot_config, $response_data)
    {

        if ($this->config['source'] == 'file') {
            return $this->fetchMessageFromFile($bot_config, $response_data);
        }


        return $this->fetchMessageFromSQL($response_data);

    }

    /**
     * Sets the endpoint for Bot API due to returned request from fetched response
     * @param $type string Type of response
     * @return string the Endpoint for Telegram
     */
    public function getEndpoint($type)
    {

        switch ($type) {

            case 'text':
                return 'sendMessage';
                break;

            case 'image':
                return 'sendPhoto';
                break;

            case 'sticker':
                return 'sendSticker';
                break;

            case 'video':
                return 'sendVideo';
                break;

            case 'audio':
                return 'sendAudio';
                break;

            default:
                return 'sendMessage';
                break;

        }

    }


    /**
     * If the driver is SQL, this method Sets the parameters that will be sent to Telegram by Guzzle
     * @param $data object the fetched data from SQL
     * @param $response_data array the response data of Telegram, because we need this data if the message will be as quote
     * @return array the array of data
     */
    private function setParams($data, $response_data)
    {

        //Let's set a blank array at first
        $out = [];

        //All these array keys are based on the available methods:
        //https://core.telegram.org/bots/api#available-methods
        switch ($data->response_type) {

            case 'text':
                array_push($out, [
                    'name'  => 'text',
                    'contents'  => $data->response_data,
                ]);
                break;

            case 'image':
                array_push($out,
                    [
                        'name' => 'photo',
                        'contents' => fopen(__DIR__ . '/assets/photo/' . $data->response_data, 'rb'),
                    ],
                    [
                        'name' => 'caption',
                        'contents' => $data->response_data,
                    ]
                );
                break;

            case 'sticker':
                array_push($out, [
                    'name'  => 'sticker',
                    'contents'  => fopen(__DIR__ . '/assets/sticker/' . $data->response_data, 'rb'),
                ]);
                break;

            case 'video':
                array_push($out, [
                    'name'  => 'video',
                    'contents' => fopen(__DIR__ . '/assets/video/' . $data->response_data, 'rb'),
                ]);
                break;

            case 'audio':
                array_push($out, [
                    'name'  => 'audio',
                    'contents'  => fopen(__DIR__ . '/assets/audio/' . $data->response_data, 'rb'),
                ]);
                break;

            default:
                array_push($out, [
                    'name'  => 'text',
                    'contents'  => $data->response_data,
                ]);
                break;

        }

        if ($data->as_quote == 'y') {
            array_push($out, [
                'name'  => 'reply_to_message_id',
                'contents'  => (string)$response_data['message']['message_id'],
            ]);
        }

        if ($data->preview_links_if_any == 'y') {
            array_push($out, [
               'name'   => 'disable_web_page_preview',
                'contents'  => 'true', //fixme bool or string, would it matter?
            ]);
        }

        return $out;
    }


    /**
     * If source is set to sql, this method runs to provide message and configuration values
     * @param $response_data
     * @return array
     */
    private function fetchMessageFromSQL($response_data)
    {
        //This is the output that will be sent to Telegram as an array
        $queryArray = [
            [
                'name' => 'chat_id',
                'contents' => (string)$response_data['message']['chat']['id'],
            ],
        ];

        //The Raw Request
        $botRequest = $response_data['message']['text'];

        $message = $this->config['default_fallback_response'];

        //This is the name of the bot
        $bot = $this->config['bots'][$this->route];

        //The text that user has typed, let's delete the bot name
        $userInput = trim(str_replace('@' . $bot, '', $botRequest));
        //TODO this will be command feature, in the future
        if (stripos($userInput, '/') !== false && stripos($userInput, $bot) === false) {
            array_push($queryArray, [
                'name'  => 'text',
                'contents'  => $message,
            ]);
            return [
                'type'  => 'text',
                'data'  => $queryArray,
            ];
        }

        //First, let's check for multiple word occurences
        $data = ORM::for_table('responses')
            ->where('bot_name', $bot)
            ->where('pattern', $userInput)
            ->order_by_expr($this->config['connection'][$this->config['source']]['rand_method'])
            ->find_one();

        if ($data) {

            $queryArray = array_merge($queryArray, $this->setParams($data, $response_data));

        } else {

            //If not found, then let's split it by spaces and trim each exploded word
            $userInputArray = array_map('trim', explode(" ", $userInput));

            $data = ORM::for_table('responses')
                ->where('bot_name', $bot)
                ->where_in('pattern', $userInputArray)
                ->order_by_expr($this->config['connection'][$this->config['source']]['rand_method'])
                ->find_one();

            if ($data) {

                $queryArray = array_merge($queryArray, $this->setParams($data, $response_data));

            } else {

                //If all fails, let's set the fallback message for text
                array_push($queryArray, [
                    'name'  => 'text',
                    'contents'  => $message,
                ]);
            }
        }

        return [
            'type' => $data ? $data->response_type : 'text',
            'data' => $queryArray,
        ];

    }

    /**
     * If source is set to file, this method runs to provide message and configuration values
     * @param $bot_config
     * @param $response_data
     * @return array Array that will be sent to Telegram
     */
    private function fetchMessageFromFile($bot_config, $response_data)
    {

        //This is the output that will be sent to Telegram as an array
        $queryArray = [
            'chat_id' => (string)$response_data['message']['chat']['id'],
        ];

        $message = $this->config['default_fallback_response'];

        if (isset($bot_config['responses'])) {

            //The raw request
            $botRequest = $response_data['message']['text'];

            //prevent the reusage
            $botMessages = $bot_config['responses'];

            //Set the message, and if responses are not found, fall back to default message
            $message = $botMessages['fallback_response'];

            //This is the name of the bot
            $bot = $this->config['bots'][$this->route];

            //The text that user has typed, let's delete the bot name
            $userInput = trim(str_replace('@' . $bot, '', $botRequest));
            //If user only says "/" it also triggers the webhook, we don't want to return anything for now
            //TODO this will be command feature, in the future
            if (stripos($userInput, '/') !== false && stripos($userInput, $bot) === false) {
                $queryArray['text'] = $message;
                return [
                    'type'  => 'text',
                    'data'  => $queryArray,
                ];
            }

            //First, let's check for multiple word occurences
            if (array_key_exists($userInput, $botMessages)) {
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
        }

        //Now let's set the message
        $queryArray['text'] = $message;

        if ($bot_config['as_reply']) {
            $queryArray['reply_to_message_id'] = (string)$response_data['message']['message_id'];
        }

        if (!$bot_config['preview_links']) {
            $queryArray['disable_web_page_preview'] = true;
        }

        return [
            'type' => 'text',
            'data' => $queryArray,
        ];

    }

}